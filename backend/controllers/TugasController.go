package controllers

import (
	"SIOPLAS/config"
	"SIOPLAS/models"
	"fmt"
	"net/http"
	"time"

	"github.com/gin-gonic/gin"
)

// CreateTugas creates a new task and triggers notifications
func CreateTugas(c *gin.Context) {
	var input models.Tugas
	if err := c.ShouldBindJSON(&input); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Input tidak valid"})
		return
	}

	// Set timestamps if not provided (though GORM usually handles this if tagged, explicit is safer for logic)
	if input.Tanggal.IsZero() {
		input.Tanggal = time.Now()
	}

	if err := config.DB.Create(&input).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal membuat tugas"})
		return
	}

	// Trigger Notifications asynchronously
	go func(tugasId uint, judul, divisi, penerima string) {
		// 1. Notify Atasan (Semua atasan atau satu?)
		// Biasanya Atasan perlu tahu semua, tapi kita filter jika perlu. Sementara kirim ke semua Atasan.
		var atasans []models.Atasan
		config.DB.Find(&atasans)
		for _, u := range atasans {
			CreateNotification(fmt.Sprint(tugasId), fmt.Sprintf("atasan-%d", u.Id), "Tugas Baru", fmt.Sprintf("Tugas baru '%s' telah dibuat.", judul), "unread")
		}

		// 2. Jika ditujukan ke DIVISI
		if divisi != "" && divisi != "Pilih Divisi" {
			// Notify Katimja sesuai Divisi
			var katimjas []models.Katimja
			// Cari Katimja yang divisinya cocok, ATAU jabatannya mengandung nama divisi (fallback)
			if err := config.DB.Where("divisi = ? OR jabatan LIKE ?", divisi, "%"+divisi+"%").Find(&katimjas).Error; err == nil {
				fmt.Printf("Found %d Katimja for division %s\n", len(katimjas), divisi) // Logging debug
				for _, u := range katimjas {
					CreateNotification(fmt.Sprint(tugasId), fmt.Sprintf("katimja-%d", u.Id), "Tugas Baru", fmt.Sprintf("Tugas '%s' masuk ke divisi Anda (%s).", judul, divisi), "unread")
				}
			} else {
				fmt.Println("Error finding Katimja:", err)
			}

			// Notify Staff di divisi tersebut
			// (Asumsi Staff juga punya Divisi atau dicari via Jabatan)
			var staffs []models.Staff
			config.DB.Where("jabatan LIKE ?", "%"+divisi+"%").Find(&staffs)
			for _, u := range staffs {
				CreateNotification(fmt.Sprint(tugasId), fmt.Sprintf("staff-%d", u.Id), "Tugas Baru", fmt.Sprintf("Tugas '%s' ditugaskan ke divisi Anda.", judul), "unread")
			}
		}

		// 3. Jika ditujukan ke PEGAWAI (Spesifik)
		if penerima != "" && penerima != "Pilih Pegawai" {
			// Cek di Staff
			var staff models.Staff
			if err := config.DB.Where("nama = ?", penerima).First(&staff).Error; err == nil {
				CreateNotification(fmt.Sprint(tugasId), fmt.Sprintf("staff-%d", staff.Id), "Tugas Personal", fmt.Sprintf("Anda diberi tugas baru: '%s'", judul), "unread")
			}
			// Cek di Katimja (jika bisa ditugaskan ke Katimja)
			var katimja models.Katimja
			if err := config.DB.Where("nama = ?", penerima).First(&katimja).Error; err == nil {
				CreateNotification(fmt.Sprint(tugasId), fmt.Sprintf("katimja-%d", katimja.Id), "Tugas Personal", fmt.Sprintf("Anda diberi tugas baru: '%s'", judul), "unread")
			}
		}
	}(input.Id, input.Judul, input.Divisi, input.Penerima)

	c.JSON(http.StatusOK, gin.H{"status": "success", "message": "Tugas berhasil dibuat", "data": input})
}

// GetAllTugas retrieves all tasks
func GetAllTugas(c *gin.Context) {
	var tugasList []models.Tugas
	if err := config.DB.Find(&tugasList).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal mengambil data tugas"})
		return
	}
	c.JSON(http.StatusOK, gin.H{"status": "success", "data": tugasList})
}

// GetTugasById retrieves a single task
func GetTugasById(c *gin.Context) {
	id := c.Param("id")
	var tugas models.Tugas
	if err := config.DB.First(&tugas, id).Error; err != nil {
		c.JSON(http.StatusNotFound, gin.H{"error": "Tugas tidak ditemukan"})
		return
	}
	c.JSON(http.StatusOK, gin.H{"status": "success", "data": tugas})
}

// GetTugasByUser retrieves tasks for a specific user (by name or division)
func GetTugasByUser(c *gin.Context) {
	nama := c.Query("nama")
	divisi := c.Query("divisi")

	var tugasList []models.Tugas

	// Logic: Ambil tugas yang Penerima == Nama User OR Divisi == Divisi User
	if err := config.DB.Where("penerima = ? OR (divisi != '' AND divisi = ?)", nama, divisi).Find(&tugasList).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal mengambil data tugas"})
		return
	}
	c.JSON(http.StatusOK, gin.H{"status": "success", "data": tugasList})
}

// UpdateTugasSelesai mark task as done and save result file
func UpdateTugasSelesai(c *gin.Context) {
	id := c.Param("id")
	var tugas models.Tugas
	if err := config.DB.First(&tugas, id).Error; err != nil {
		c.JSON(http.StatusNotFound, gin.H{"error": "Tugas tidak ditemukan"})
		return
	}

	var input struct {
		FileSelesai string `json:"file_selesai"`
		Role        string `json:"role"` // "staff" or "katimja"
	}
	if err := c.ShouldBindJSON(&input); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Input tidak valid"})
		return
	}

	if input.FileSelesai != "" {
		tugas.FileSelesai = input.FileSelesai
	}
	tugas.Status = "Selesai" // Or "Menunggu Review" if complex workflow

	if err := config.DB.Save(&tugas).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal update tugas"})
		return
	}

	// NOTIFICATION LOGIC BASED ON ROLE
	if input.Role == "staff" {
		// If Staff finished -> Notify Katimja (Reviewer)
		var katimjas []models.Katimja
		if err := config.DB.Where("divisi = ?", tugas.Divisi).Find(&katimjas).Error; err == nil {
			for _, k := range katimjas {
				go CreateNotification(fmt.Sprint(tugas.Id), fmt.Sprintf("katimja-%d", k.Id), "Tugas Selesai (Staff)", fmt.Sprintf("Staff telah menyelesaikan tugas '%s'. Harap diperiksa.", tugas.Judul), "unread")
			}
		}
	} else if input.Role == "katimja" {
		// If Katimja verified/finished -> Notify Atasan (Pembuat)
		// Assuming Pembuat name is unique or we can find Atasan by name
		// Ideally we have PembuatID, but user only stores Check string.
		// Broadcast to all Atasan or try to find specific one.
		var atasans []models.Atasan
		config.DB.Find(&atasans)
		for _, u := range atasans {
			// Optional: Filter by name if needed, e.g. if u.Nama == tugas.Pembuat
			if tugas.Pembuat == "" || u.Nama == tugas.Pembuat {
				go CreateNotification(fmt.Sprint(tugas.Id), fmt.Sprintf("atasan-%d", u.Id), "Tugas Selesai (Katimja)", fmt.Sprintf("Katimja telah memverifikasi tugas '%s'.", tugas.Judul), "unread")
			}
		}
	}

	c.JSON(http.StatusOK, gin.H{"status": "success", "message": "Tugas selesai", "data": tugas})
}

// UpdateTugasRespon handles Atasan's response/revision
func UpdateTugasRespon(c *gin.Context) {
	id := c.Param("id")
	var tugas models.Tugas
	if err := config.DB.First(&tugas, id).Error; err != nil {
		c.JSON(http.StatusNotFound, gin.H{"error": "Tugas tidak ditemukan"})
		return
	}

	var input struct {
		Respon     string `json:"respon"`
		FileRespon string `json:"file_respon"`
	}

	if err := c.ShouldBindJSON(&input); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Input tidak valid"})
		return
	}

	tugas.Respon = input.Respon
	if input.FileRespon != "" {
		tugas.FileRespon = input.FileRespon
	}

	// Update Status if needed (e.g. back to In Progress or Revision)
	tugas.Status = "Revisi"

	if err := config.DB.Save(&tugas).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal menyimpan respon"})
		return
	}

	// NOTIFIKASI
	// Kirim notifikasi ke Penerima (Staff/Katimja)
	// Kita perlu tahu ID user penerima.
	// Logic sederhana: cari di Staff atau Katimja berdasarkan nama 'Penerima' or 'Divisi'
	go func(tId uint, tugasData models.Tugas, msg string) {
		// 1. Notify Staff (Penerima)
		var staff models.Staff
		if err := config.DB.Where("nama = ?", tugasData.Penerima).First(&staff).Error; err == nil {
			CreateNotification(fmt.Sprint(tId), fmt.Sprintf("staff-%d", staff.Id), "Respon Atasan", msg, "unread")
		}

		// 2. Notify Katimja (Supervisor) based on Divisi
		var katimjas []models.Katimja
		if err := config.DB.Where("divisi = ?", tugasData.Divisi).Find(&katimjas).Error; err == nil {
			for _, k := range katimjas {
				CreateNotification(fmt.Sprint(tId), fmt.Sprintf("katimja-%d", k.Id), "Respon Atasan", "Atasan telah mereview tugas staff Anda. Cek detail.", "unread")
			}
		}
	}(tugas.Id, tugas, fmt.Sprintf("Atasan memberikan respon pada tugas '%s'. Cek detail tugas.", tugas.Judul))

	c.JSON(http.StatusOK, gin.H{"status": "success", "message": "Respon berhasil dikirim", "data": tugas})
}

// UpdateTugas updates task details (e.g. assigning to staff)
func UpdateTugas(c *gin.Context) {
	id := c.Param("id")
	var tugas models.Tugas
	if err := config.DB.First(&tugas, id).Error; err != nil {
		c.JSON(http.StatusNotFound, gin.H{"error": "Tugas tidak ditemukan"})
		return
	}

	var input struct {
		Penerima string `json:"penerima"`
	}

	if err := c.ShouldBindJSON(&input); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Input tidak valid"})
		return
	}

	if input.Penerima != "" {
		tugas.Penerima = input.Penerima

		// Notifikasi ke Staff yang baru ditunjuk
		// Cari Staff berdasarkan Nama
		var staff models.Staff
		if err := config.DB.Where("nama = ?", input.Penerima).First(&staff).Error; err == nil {
			go CreateNotification(fmt.Sprint(tugas.Id), fmt.Sprintf("staff-%d", staff.Id), "Tugas Baru", fmt.Sprintf("Anda ditugaskan pada: '%s'", tugas.Judul), "unread")
		}
	}

	if err := config.DB.Save(&tugas).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal update tugas"})
		return
	}

	c.JSON(http.StatusOK, gin.H{"status": "success", "data": tugas})
}
