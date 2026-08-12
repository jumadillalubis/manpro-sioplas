package controllers

import (
	"SIOPLAS/config"
	"SIOPLAS/models"
	"fmt"
	"net/http"
	"sort"
	"time"

	"github.com/gin-gonic/gin"
)

// Helper DTO
type TugasInput struct {
	Id              uint      `json:"id"`
	Pembuat         string    `json:"pembuat"`
	Judul           string    `json:"judul"`
	Deskripsi       string    `json:"deskripsi"`
	FileTugas       string    `json:"file_tugas"`
	FileSelesai     string    `json:"file_selesai"`
	FileSelesaiOleh string    `json:"file_selesai_oleh"`
	Status          string    `json:"status"`
	Divisi          string    `json:"divisi"`
	Penerima        string    `json:"penerima"`
	Deadline        time.Time `json:"deadline"`
	Tanggal         time.Time `json:"tanggal_buat_tugas"`
	Respon          string    `json:"respon"`
	FileRespon      string    `json:"file_respon"`

	// Optional specific source or type
	TipeTugas string `json:"tipe_tugas"`
}

func (i TugasInput) ToAtasan() models.Tugas_Atasan {
	return models.Tugas_Atasan{
		Pembuat:    i.Pembuat,
		Judul:      i.Judul,
		Deskripsi:  i.Deskripsi,
		FileTugas:  i.FileTugas,
		Status:     i.Status,
		Divisi:     i.Divisi,
		Penerima:   i.Penerima,
		Deadline:   i.Deadline,
		Tanggal:    i.Tanggal,
		Respon:     i.Respon,
		FileRespon: i.FileRespon,
	}
}

func (i TugasInput) ToKatimja() models.Tugas_Katimja {
	return models.Tugas_Katimja{
		Pembuat:    i.Pembuat,
		Judul:      i.Judul,
		Deskripsi:  i.Deskripsi,
		FileTugas:  i.FileTugas,
		Status:     i.Status,
		Divisi:     i.Divisi,
		Penerima:   i.Penerima,
		Deadline:   i.Deadline,
		Tanggal:    i.Tanggal,
		Respon:     i.Respon,
		FileRespon: i.FileRespon,
		Type:       i.TipeTugas, // Map TipeTugas input to Type column
	}
}

// CreateTugas creates a new task in the appropriate table
func CreateTugas(c *gin.Context) {
	var input TugasInput
	if err := c.ShouldBindJSON(&input); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Input tidak valid"})
		return
	}

	if input.Tanggal.IsZero() {
		input.Tanggal = time.Now()
	}
	if input.Status == "" {
		input.Status = "Pending"
	}

	// Detect Source
	// If 'tipe_tugas' is explicit (from Katimja forms), ok.
	// Or check Pembuat against Atasan table.
	isAtasan := false

	// Logic: If Pembuat is in Atasan table -> Atasan Task.
	// If Pembuat is NOT Atasan (e.g. Katimja name) -> Katimja Task.
	var atasan models.Atasan
	if err := config.DB.Where("nama = ?", input.Pembuat).First(&atasan).Error; err == nil {
		isAtasan = true
	}

	var savedId uint
	var source string

	if isAtasan {
		tugas := input.ToAtasan()
		if err := config.DB.Create(&tugas).Error; err != nil {
			c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal membuat tugas atasan"})
			return
		}
		savedId = tugas.Id
		source = "atasan"
	} else {
		// Assume Katimja
		tugas := input.ToKatimja()
		if err := config.DB.Create(&tugas).Error; err != nil {
			c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal membuat tugas katimja"})
			return
		}
		savedId = tugas.Id
		source = "katimja"
	}

	// Notifications
	go sendNotifications(savedId, source, input)

	c.JSON(http.StatusOK, gin.H{"status": "success", "message": "Tugas berhasil dibuat", "data": map[string]interface{}{
		"id": savedId, "source": source,
	}})
}

// GetAllTugas retrieves all tasks from both tables
func GetAllTugas(c *gin.Context) {
	var tugasAtasan []models.Tugas_Atasan
	var tugasKatimja []models.Tugas_Katimja

	config.DB.Find(&tugasAtasan)
	config.DB.Find(&tugasKatimja)

	var combined []map[string]interface{}

	for _, t := range tugasAtasan {
		combined = append(combined, map[string]interface{}{
			"id": t.Id, "source": "atasan",
			"judul": t.Judul, "pembuat": t.Pembuat, "status": t.Status,
			"deadline": t.Deadline, "tanggal_buat_tugas": t.Tanggal,
			"divisi": t.Divisi, "penerima": t.Penerima,
			"file_selesai": t.FileSelesai, "file_selesai_oleh": t.FileSelesaiOleh,
			"respon": t.Respon, "file_respon": t.FileRespon, "deskripsi": t.Deskripsi, "file_tugas": t.FileTugas,
		})
	}
	for _, t := range tugasKatimja {
		combined = append(combined, map[string]interface{}{
			"id": t.Id, "source": "katimja",
			"judul": t.Judul, "pembuat": t.Pembuat, "status": t.Status,
			"deadline": t.Deadline, "tanggal_buat_tugas": t.Tanggal,
			"divisi": t.Divisi, "penerima": t.Penerima,
			"file_selesai": t.FileSelesai, "file_selesai_oleh": t.FileSelesaiOleh,
			"respon": t.Respon, "file_respon": t.FileRespon, "deskripsi": t.Deskripsi, "file_tugas": t.FileTugas,
		})
	}

	// Sort by Date Descending
	sort.Slice(combined, func(i, j int) bool {
		t1, _ := combined[i]["tanggal_buat_tugas"].(time.Time)
		t2, _ := combined[j]["tanggal_buat_tugas"].(time.Time)
		return t1.After(t2)
	})

	c.JSON(http.StatusOK, gin.H{"status": "success", "data": combined})
}

// GetTugasById retrieves a single task
// MUST provide ?source=atasan or ?source=katimja
func GetTugasById(c *gin.Context) {
	id := c.Param("id")
	source := c.Query("source")

	if source == "atasan" {
		var tugas models.Tugas_Atasan
		if err := config.DB.First(&tugas, id).Error; err == nil {
			c.JSON(http.StatusOK, gin.H{"status": "success", "data": tugas, "source": "atasan"})
			return
		}
	} else if source == "katimja" {
		var tugas models.Tugas_Katimja
		if err := config.DB.First(&tugas, id).Error; err == nil {
			c.JSON(http.StatusOK, gin.H{"status": "success", "data": tugas, "source": "katimja"})
			return
		}
	} else {
		// Fallback: Try both (Risk of collision)
		var tAtasan models.Tugas_Atasan
		if err := config.DB.First(&tAtasan, id).Error; err == nil {
			c.JSON(http.StatusOK, gin.H{"status": "success", "data": tAtasan, "source": "atasan"})
			return
		}
		var tKatimja models.Tugas_Katimja
		if err := config.DB.First(&tKatimja, id).Error; err == nil {
			c.JSON(http.StatusOK, gin.H{"status": "success", "data": tKatimja, "source": "katimja"})
			return
		}
	}

	c.JSON(http.StatusNotFound, gin.H{"error": "Tugas tidak ditemukan"})
}

// GetTugasByUser retrieves tasks for a specific user
func GetTugasByUser(c *gin.Context) {
	// Reusing GetAllTugas logic efficiently by filtering in DB?
	// But we need to check both tables.
	// Simplest: Call the same logic as GetAllTugas but filtered.
	// Filter: Penerima = Name OR Divisi = Divisi

	nama := c.Query("nama")
	divisi := c.Query("divisi")

	var tugasAtasan []models.Tugas_Atasan
	config.DB.Where("penerima = ? OR (divisi != '' AND divisi = ?)", nama, divisi).Find(&tugasAtasan)

	var tugasKatimja []models.Tugas_Katimja
	config.DB.Where("penerima = ? OR (divisi != '' AND divisi = ?)", nama, divisi).Find(&tugasKatimja)

	var combined []map[string]interface{}
	for _, t := range tugasAtasan {
		combined = append(combined, map[string]interface{}{
			"id": t.Id, "source": "atasan",
			"judul": t.Judul, "pembuat": t.Pembuat, "status": t.Status, "deadline": t.Deadline, "tanggal_buat_tugas": t.Tanggal,
			"divisi": t.Divisi, "penerima": t.Penerima,
		})
	}
	for _, t := range tugasKatimja {
		combined = append(combined, map[string]interface{}{
			"id": t.Id, "source": "katimja",
			"judul": t.Judul, "pembuat": t.Pembuat, "status": t.Status, "deadline": t.Deadline, "tanggal_buat_tugas": t.Tanggal,
			"divisi": t.Divisi, "penerima": t.Penerima,
		})
	}

	c.JSON(http.StatusOK, gin.H{"status": "success", "data": combined})
}

func saveTugasAtasan(t *models.Tugas_Atasan) error {
	var count int64
	config.DB.Model(&models.Tugas_Atasan{}).Where("id = ?", t.Id).Count(&count)
	if count > 0 {
		return config.DB.Model(t).Where("id = ?", t.Id).Updates(map[string]interface{}{
			"pembuat":           t.Pembuat,
			"judul":             t.Judul,
			"deskripsi":         t.Deskripsi,
			"file_tugas":        t.FileTugas,
			"file_selesai":      t.FileSelesai,
			"file_selesai_oleh": t.FileSelesaiOleh,
			"status":            t.Status,
			"divisi":            t.Divisi,
			"penerima":          t.Penerima,
			"deadline":          t.Deadline,
			"tanggal":           t.Tanggal,
			"respon":            t.Respon,
			"file_respon":       t.FileRespon,
		}).Error
	}
	return config.DB.Create(t).Error
}

func saveTugasKatimja(t *models.Tugas_Katimja) error {
	var count int64
	config.DB.Model(&models.Tugas_Katimja{}).Where("id = ?", t.Id).Count(&count)
	if count > 0 {
		return config.DB.Model(t).Where("id = ?", t.Id).Updates(map[string]interface{}{
			"pembuat":           t.Pembuat,
			"judul":             t.Judul,
			"deskripsi":         t.Deskripsi,
			"file_tugas":        t.FileTugas,
			"file_selesai":      t.FileSelesai,
			"file_selesai_oleh": t.FileSelesaiOleh,
			"status":            t.Status,
			"divisi":            t.Divisi,
			"penerima":          t.Penerima,
			"deadline":          t.Deadline,
			"tanggal":           t.Tanggal,
			"respon":            t.Respon,
			"file_respon":       t.FileRespon,
			"type":              t.Type,
		}).Error
	}
	return config.DB.Create(t).Error
}

// UpdateTugasSelesai
func UpdateTugasSelesai(c *gin.Context) {
	id := c.Param("id")
	source := c.Query("source") // Must be passed by frontend

	var input struct {
		FileSelesai string `json:"file_selesai"`
		Role        string `json:"role"`
		Uploader    string `json:"uploader"`
	}
	if err := c.ShouldBindJSON(&input); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Input tidak valid"})
		return
	}

	// Helper function to update and notify
	update := func(tugas interface{}, table string) {
		// Reflection or Type Switch needed if using interface, but code duplication is safer here
	}
	_ = update

	if source == "atasan" {
		var t models.Tugas_Atasan
		if err := config.DB.First(&t, id).Error; err != nil {
			c.JSON(http.StatusNotFound, gin.H{"error": "Tugas not found"})
			return
		}
		if input.FileSelesai != "" {
			t.FileSelesai = input.FileSelesai
		}
		if input.Uploader != "" {
			t.FileSelesaiOleh = input.Uploader
		}

		// Workflow Logic for Atasan Task
		if input.Role == "katimja" || t.Penerima != "" {
			t.Status = "Menunggu Approval" // Forwarded to Atasan (or direct perorangan staff task)
			notifySelesai(t.Id, t.Judul, t.Divisi, t.Pembuat, input.Role, source)
		} else {
			t.Status = "Menunggu Review" // Staff submitted division task, waiting for Katimja
			notifySelesai(t.Id, t.Judul, t.Divisi, t.Pembuat, input.Role, source)
		}

		saveTugasAtasan(&t)
		config.DB.Where("tugas_id = ?", fmt.Sprint(t.Id)).Delete(&models.Notification{})
		c.JSON(http.StatusOK, gin.H{"status": "success", "data": t})
		return
	}

	// Default/Katimja
	var t models.Tugas_Katimja
	if err := config.DB.First(&t, id).Error; err != nil {
		// Retry Atasan if source missing?
		var t2 models.Tugas_Atasan
		if err := config.DB.First(&t2, id).Error; err == nil {
			// Found in Atasan
			if input.FileSelesai != "" {
				t2.FileSelesai = input.FileSelesai
			}
			if input.Uploader != "" {
				t2.FileSelesaiOleh = input.Uploader
			}
			if input.Role == "katimja" || t2.Penerima != "" {
				t2.Status = "Menunggu Approval"
			} else {
				t2.Status = "Menunggu Review"
			}
			saveTugasAtasan(&t2)
			config.DB.Where("tugas_id = ?", fmt.Sprint(t2.Id)).Delete(&models.Notification{})
			notifySelesai(t2.Id, t2.Judul, t2.Divisi, t2.Pembuat, input.Role, "atasan")
			c.JSON(http.StatusOK, gin.H{"status": "success", "data": t2})
			return
		}
		c.JSON(http.StatusNotFound, gin.H{"error": "Tugas not found"})
		return
	}
	if input.FileSelesai != "" {
		t.FileSelesai = input.FileSelesai
	}
	if input.Uploader != "" {
		t.FileSelesaiOleh = input.Uploader
	}
	t.Status = "Menunggu Review"
	saveTugasKatimja(&t)
	config.DB.Where("tugas_id = ?", fmt.Sprint(t.Id)).Delete(&models.Notification{})
	notifySelesai(t.Id, t.Judul, t.Divisi, t.Pembuat, input.Role, "katimja")
	c.JSON(http.StatusOK, gin.H{"status": "success", "data": t})
}

// UpdateTugasRespon
func UpdateTugasRespon(c *gin.Context) {
	id := c.Param("id")
	source := c.Query("source") // atasan or katimja (task source)

	var input struct {
		Respon     string `json:"respon"`
		FileRespon string `json:"file_respon"`
	}
	if err := c.ShouldBindJSON(&input); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Input tidak valid"})
		return
	}

	if source == "katimja" { // Responding to Katimja Task
		var t models.Tugas_Katimja
		if err := config.DB.First(&t, id).Error; err != nil {
			c.JSON(http.StatusNotFound, gin.H{"error": "Tugas not found"})
			return
		}
		t.Respon = input.Respon
		if input.FileRespon != "" {
			t.FileRespon = input.FileRespon
		}
		t.Status = "Revisi"
		saveTugasKatimja(&t)
		notifyRespon(t.Id, t.Penerima, t.Divisi, t.Judul, input.Respon, "katimja")
		c.JSON(http.StatusOK, gin.H{"status": "success", "data": t})
		return
	}

	// Default Atasan
	var t models.Tugas_Atasan
	if err := config.DB.First(&t, id).Error; err != nil {
		c.JSON(http.StatusNotFound, gin.H{"error": "Tugas not found"})
		return
	}
	t.Respon = input.Respon
	if input.FileRespon != "" {
		t.FileRespon = input.FileRespon
	}
	t.Status = "Revisi"
	saveTugasAtasan(&t)
	notifyRespon(t.Id, t.Penerima, t.Divisi, t.Judul, input.Respon, "atasan")
	c.JSON(http.StatusOK, gin.H{"status": "success", "data": t})
}

// UpdateTugas (Assign)
func UpdateTugas(c *gin.Context) {
	id := c.Param("id")
	source := c.Query("source")

	var input struct {
		Penerima string `json:"penerima"`
	}
	if err := c.ShouldBindJSON(&input); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Input tidak valid"})
		return
	}

	if source == "katimja" {
		var t models.Tugas_Katimja
		if err := config.DB.First(&t, id).Error; err != nil {
			c.JSON(http.StatusNotFound, gin.H{"error": "Tugas not found"})
			return
		}
		t.Penerima = input.Penerima
		saveTugasKatimja(&t)
		// Notify staff
		var staff models.Staff
		if err := config.DB.Where("nama = ?", input.Penerima).First(&staff).Error; err == nil {
			CreateNotification(fmt.Sprint(t.Id), fmt.Sprintf("staff-%d", staff.Id), "Tugas Baru", fmt.Sprintf("Anda ditugaskan pada: '%s'", t.Judul), "unread")
		}
		c.JSON(http.StatusOK, gin.H{"status": "success", "data": t})
		return
	}

	// Default Atasan
	var t models.Tugas_Atasan
	if err := config.DB.First(&t, id).Error; err != nil {
		c.JSON(http.StatusNotFound, gin.H{"error": "Tugas not found"})
		return
	}
	t.Penerima = input.Penerima
	saveTugasAtasan(&t)
	var staff models.Staff
	if err := config.DB.Where("nama = ?", input.Penerima).First(&staff).Error; err == nil {
		CreateNotification(fmt.Sprint(t.Id), fmt.Sprintf("staff-%d", staff.Id), "Tugas Baru", fmt.Sprintf("Anda ditugaskan pada: '%s'", t.Judul), "unread")
	}
	c.JSON(http.StatusOK, gin.H{"status": "success", "data": t})
}

// ApproveTugas (For Atasan)
func ApproveTugas(c *gin.Context) {
	id := c.Param("id")
	// Only for Atasan Tasks
	var t models.Tugas_Atasan
	if err := config.DB.First(&t, id).Error; err != nil {
		c.JSON(http.StatusNotFound, gin.H{"error": "Tugas Atasan tidak ditemukan"})
		return
	}
	t.Status = "Selesai"
	saveTugasAtasan(&t)
	config.DB.Where("tugas_id = ?", fmt.Sprint(t.Id)).Delete(&models.Notification{})
	notifyApproved(t.Id, t.Judul, t.Divisi, t.Penerima, "atasan")
	c.JSON(http.StatusOK, gin.H{"status": "success", "message": "Tugas berhasil disetujui", "data": t})
}

// ApproveTugasKatimja (For Katimja)
func ApproveTugasKatimja(c *gin.Context) {
	id := c.Param("id")
	// Check if it's a Katimja Task
	var t models.Tugas_Katimja
	if err := config.DB.First(&t, id).Error; err != nil {
		// If not found in Katimja tasks, check Atasan tasks!
		var tAtasan models.Tugas_Atasan
		if err := config.DB.First(&tAtasan, id).Error; err == nil {
			tAtasan.Status = "Menunggu Approval"
			saveTugasAtasan(&tAtasan)
			config.DB.Where("tugas_id = ?", fmt.Sprint(tAtasan.Id)).Delete(&models.Notification{})
			notifySelesai(tAtasan.Id, tAtasan.Judul, tAtasan.Divisi, tAtasan.Pembuat, "katimja", "atasan")
			c.JSON(http.StatusOK, gin.H{"status": "success", "message": "Tugas berhasil diteruskan ke Atasan", "data": tAtasan})
			return
		}

		c.JSON(http.StatusNotFound, gin.H{"error": "Tugas tidak ditemukan"})
		return
	}
	t.Status = "Selesai"
	saveTugasKatimja(&t)
	config.DB.Where("tugas_id = ?", fmt.Sprint(t.Id)).Delete(&models.Notification{})
	notifyApproved(t.Id, t.Judul, t.Divisi, t.Penerima, "katimja")
	c.JSON(http.StatusOK, gin.H{"status": "success", "message": "Tugas berhasil disetujui", "data": t})
}


// --- Notification Helpers ---

func sendNotifications(tId uint, source string, input TugasInput) {
	// Common logic
	judul := input.Judul
	divisi := input.Divisi
	penerima := input.Penerima

	msgSuffix := " (Dari Atasan)"
	if source == "katimja" {
		msgSuffix = " (Dari Katimja)"
	}

	if divisi != "" && divisi != "Pilih Divisi" {
		if source == "atasan" {
			var katimjas []models.Katimja
			if err := config.DB.Where("divisi = ? OR jabatan LIKE ?", divisi, "%"+divisi+"%").Find(&katimjas).Error; err == nil {
				for _, u := range katimjas {
					CreateNotification(fmt.Sprint(tId), fmt.Sprintf("katimja-%d", u.Id), "Tugas Baru", fmt.Sprintf("Tugas '%s' masuk ke divisi Anda.%s", judul, msgSuffix), "unread")
				}
			}
		}
		var staffs []models.Staff
		config.DB.Where("jabatan LIKE ?", "%"+divisi+"%").Find(&staffs)
		for _, u := range staffs {
			CreateNotification(fmt.Sprint(tId), fmt.Sprintf("staff-%d", u.Id), "Tugas Baru", fmt.Sprintf("Tugas '%s' ditugaskan ke divisi Anda.%s", judul, msgSuffix), "unread")
		}
	}

	if penerima != "" {
		var staff models.Staff
		if err := config.DB.Where("nama = ?", penerima).First(&staff).Error; err == nil {
			CreateNotification(fmt.Sprint(tId), fmt.Sprintf("staff-%d", staff.Id), "Tugas Personal", fmt.Sprintf("Anda diberi tugas baru: '%s'", judul), "unread")
		}
	}
}

func notifySelesai(tId uint, judul, divisi, pembuat, role, source string) {
	if role == "staff" {
		if divisi != "" {
			var katimjas []models.Katimja
			if err := config.DB.Where("divisi = ?", divisi).Find(&katimjas).Error; err == nil {
				for _, k := range katimjas {
					go CreateNotification(fmt.Sprint(tId), fmt.Sprintf("katimja-%d", k.Id), "Tugas Selesai (Staff)", fmt.Sprintf("Staff telah menyelesaikan tugas '%s'. Harap diperiksa.", judul), "unread")
				}
			}
		}
		if source == "atasan" || divisi == "" {
			var atasans []models.Atasan
			config.DB.Find(&atasans)
			for _, u := range atasans {
				if pembuat == "" || u.Nama == pembuat {
					go CreateNotification(fmt.Sprint(tId), fmt.Sprintf("atasan-%d", u.Id), "Tugas Butuh Persetujuan", fmt.Sprintf("Staff telah mengunggah pengerjaan tugas '%s'. Menunggu persetujuan Anda.", judul), "unread")
				}
			}
		}
	} else if role == "katimja" && source == "atasan" {
		var atasans []models.Atasan
		config.DB.Find(&atasans)
		for _, u := range atasans {
			if pembuat == "" || u.Nama == pembuat {
				go CreateNotification(fmt.Sprint(tId), fmt.Sprintf("atasan-%d", u.Id), "Tugas Butuh Persetujuan", fmt.Sprintf("Katimja telah meneruskan tugas '%s'. Menunggu persetujuan Anda.", judul), "unread")
			}
		}
	}
}

func notifyRespon(tId uint, penerima, divisi, judul, msg, source string) {
	var staff models.Staff
	if err := config.DB.Where("nama = ?", penerima).First(&staff).Error; err == nil {
		CreateNotification(fmt.Sprint(tId), fmt.Sprintf("staff-%d", staff.Id), "Respon Atasan", "Tugas direvisi/direspon.", "unread")
	}
	var katimjas []models.Katimja
	if err := config.DB.Where("divisi = ?", divisi).Find(&katimjas).Error; err == nil {
		for _, k := range katimjas {
			CreateNotification(fmt.Sprint(tId), fmt.Sprintf("katimja-%d", k.Id), "Respon Atasan", "Tugas direvisi/direspon.", "unread")
		}
	}
}

func notifyApproved(tId uint, judul, divisi, penerima, source string) {
	msg := fmt.Sprintf("Tugas '%s' telah disetujui.", judul)
	var staff models.Staff
	if err := config.DB.Where("nama = ?", penerima).First(&staff).Error; err == nil {
		CreateNotification(fmt.Sprint(tId), fmt.Sprintf("staff-%d", staff.Id), "Tugas Disetujui", msg, "unread")
	}
	var katimjas []models.Katimja
	if err := config.DB.Where("divisi = ?", divisi).Find(&katimjas).Error; err == nil {
		for _, k := range katimjas {
			CreateNotification(fmt.Sprint(tId), fmt.Sprintf("katimja-%d", k.Id), "Tugas Disetujui", msg, "unread")
		}
	}
}
