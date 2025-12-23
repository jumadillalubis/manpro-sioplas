package controllers

import (
	"SIOPLAS/config"
	"SIOPLAS/models"
	"net/http"
	"time"

	"github.com/gin-gonic/gin"
)

// CreateTugasRequest untuk membuat tugas baru
type CreateTugasRequest struct {
	PembuatId    uint   `json:"pembuat_id" binding:"required"`
	Judul        string `json:"judul" binding:"required"`
	Deskripsi    string `json:"deskripsi"`
	FileTugas    string `json:"file_tugas"`
	Deadline     string `json:"deadline" binding:"required"`
	Tenggat      string `json:"tenggat" binding:"required"`
	PenerimaIds  []uint `json:"penerima_ids" binding:"required"`  // Array ID katimja atau staff
	PenerimaTipe string `json:"penerima_tipe" binding:"required"` // "katimja" atau "staff"
}

// CreateTugas membuat tugas baru oleh atasan dan mengirim notifikasi
func CreateTugas(c *gin.Context) {
	var req CreateTugasRequest

	if err := c.ShouldBindJSON(&req); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Input tidak valid: " + err.Error()})
		return
	}

	// Validasi pembuat (harus atasan)
	var atasan models.Atasan
	if err := config.DB.First(&atasan, req.PembuatId).Error; err != nil {
		c.JSON(http.StatusNotFound, gin.H{"error": "Atasan tidak ditemukan"})
		return
	}

	// Parse deadline dan tenggat
	deadline, err := time.Parse("2006-01-02T15:04:05Z07:00", req.Deadline)
	if err != nil {
		// Coba format lain
		deadline, err = time.Parse("2006-01-02 15:04:05", req.Deadline)
		if err != nil {
			c.JSON(http.StatusBadRequest, gin.H{"error": "Format deadline tidak valid"})
			return
		}
	}

	tenggat, err := time.Parse("2006-01-02T15:04:05Z07:00", req.Tenggat)
	if err != nil {
		// Coba format lain
		tenggat, err = time.Parse("2006-01-02 15:04:05", req.Tenggat)
		if err != nil {
			c.JSON(http.StatusBadRequest, gin.H{"error": "Format tenggat tidak valid"})
			return
		}
	}

	// Buat tugas
	tugas := models.Tugas{
		PembuatId:   req.PembuatId,
		PembuatNama: atasan.Nama,
		Judul:       req.Judul,
		Deskripsi:   req.Deskripsi,
		FileTugas:   req.FileTugas,
		Status:      "pending",
		Deadline:    deadline,
		Tanggal:     time.Now(),
		Tenggat:     tenggat,
		CreatedAt:   time.Now(),
		UpdatedAt:   time.Now(),
	}

	if err := config.DB.Create(&tugas).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal membuat tugas"})
		return
	}

	// Buat relasi tugas-penerima dan notifikasi
	var notifikasis []models.Notifikasi
	for _, penerimaId := range req.PenerimaIds {
		// Validasi penerima berdasarkan tipe
		if req.PenerimaTipe == "katimja" {
			var katimja models.Katimja
			if err := config.DB.First(&katimja, penerimaId).Error; err != nil {
				continue // Skip jika penerima tidak ditemukan
			}
		} else if req.PenerimaTipe == "staff" {
			var staff models.Staff
			if err := config.DB.First(&staff, penerimaId).Error; err != nil {
				continue // Skip jika penerima tidak ditemukan
			}
		} else {
			continue // Skip jika tipe tidak valid
		}

		// Buat relasi tugas-penerima
		tugasPenerima := models.TugasPenerima{
			TugasId:      tugas.Id,
			PenerimaId:   penerimaId,
			PenerimaTipe: req.PenerimaTipe,
			Status:       "pending",
		}
		config.DB.Create(&tugasPenerima)

		// Buat notifikasi
		notifikasi := models.Notifikasi{
			TugasId:      tugas.Id,
			PenerimaId:   penerimaId,
			PenerimaTipe: req.PenerimaTipe,
			Judul:        "Tugas Baru: " + req.Judul,
			Pesan:        "Anda menerima tugas baru dari " + atasan.Nama,
			Status:       "unread",
			CreatedAt:    time.Now(),
		}
		config.DB.Create(&notifikasi)
		notifikasis = append(notifikasis, notifikasi)
	}

	c.JSON(http.StatusOK, gin.H{
		"status":      "success",
		"message":     "Tugas berhasil dibuat dan notifikasi dikirim",
		"tugas":       tugas,
		"notifikasis": notifikasis,
	})
}

// GetTugasByPenerima mendapatkan tugas berdasarkan penerima (katimja/staff)
func GetTugasByPenerima(c *gin.Context) {
	penerimaId := c.Param("penerima_id")
	penerimaTipe := c.Query("tipe") // "katimja" atau "staff"

	if penerimaTipe != "katimja" && penerimaTipe != "staff" {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Tipe penerima harus 'katimja' atau 'staff'"})
		return
	}

	var tugasPenerimas []models.TugasPenerima
	if err := config.DB.Where("penerima_id = ? AND penerima_tipe = ?", penerimaId, penerimaTipe).Find(&tugasPenerimas).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal mengambil data tugas"})
		return
	}

	var tugasList []map[string]interface{}
	for _, tp := range tugasPenerimas {
		var tugas models.Tugas
		if err := config.DB.First(&tugas, tp.TugasId).Error; err != nil {
			continue
		}

		tugasList = append(tugasList, map[string]interface{}{
			"tugas":           tugas,
			"status_penerima": tp.Status,
		})
	}

	c.JSON(http.StatusOK, gin.H{
		"status": "success",
		"data":   tugasList,
	})
}

// GetTugasByPembuat mendapatkan tugas berdasarkan pembuat (atasan)
func GetTugasByPembuat(c *gin.Context) {
	pembuatId := c.Param("pembuat_id")

	var tugasList []models.Tugas
	if err := config.DB.Where("pembuat_id = ?", pembuatId).Order("created_at DESC").Find(&tugasList).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal mengambil data tugas"})
		return
	}

	c.JSON(http.StatusOK, gin.H{
		"status": "success",
		"data":   tugasList,
	})
}

// GetAllTugas mendapatkan semua tugas
func GetAllTugas(c *gin.Context) {
	var tugasList []models.Tugas
	if err := config.DB.Order("created_at DESC").Find(&tugasList).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal mengambil data tugas"})
		return
	}

	c.JSON(http.StatusOK, gin.H{
		"status": "success",
		"data":   tugasList,
	})
}

// GetTugasByID mendapatkan tugas berdasarkan ID
func GetTugasByID(c *gin.Context) {
	id := c.Param("id")

	var tugas models.Tugas
	if err := config.DB.First(&tugas, id).Error; err != nil {
		c.JSON(http.StatusNotFound, gin.H{"error": "Tugas tidak ditemukan"})
		return
	}

	// Ambil daftar penerima
	var penerimas []models.TugasPenerima
	config.DB.Where("tugas_id = ?", id).Find(&penerimas)

	c.JSON(http.StatusOK, gin.H{
		"status":    "success",
		"tugas":     tugas,
		"penerimas": penerimas,
	})
}

// UpdateStatusTugasPenerima update status tugas oleh penerima
type UpdateStatusRequest struct {
	Status string `json:"status" binding:"required"` // "pending", "in_progress", "completed"
}

func UpdateStatusTugasPenerima(c *gin.Context) {
	id := c.Param("id")
	var req UpdateStatusRequest

	if err := c.ShouldBindJSON(&req); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Input tidak valid"})
		return
	}

	var tugasPenerima models.TugasPenerima
	if err := config.DB.First(&tugasPenerima, id).Error; err != nil {
		c.JSON(http.StatusNotFound, gin.H{"error": "Tugas penerima tidak ditemukan"})
		return
	}

	tugasPenerima.Status = req.Status
	if err := config.DB.Save(&tugasPenerima).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal update status"})
		return
	}

	c.JSON(http.StatusOK, gin.H{
		"status":  "success",
		"message": "Status tugas berhasil diupdate",
		"data":    tugasPenerima,
	})
}

// GetNotifikasiByPenerima mendapatkan notifikasi berdasarkan penerima
func GetNotifikasiByPenerima(c *gin.Context) {
	penerimaId := c.Param("penerima_id")
	penerimaTipe := c.Query("tipe") // "katimja" atau "staff"

	if penerimaTipe != "katimja" && penerimaTipe != "staff" {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Tipe penerima harus 'katimja' atau 'staff'"})
		return
	}

	var notifikasis []models.Notifikasi
	if err := config.DB.Where("penerima_id = ? AND penerima_tipe = ?", penerimaId, penerimaTipe).
		Order("created_at DESC").Find(&notifikasis).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal mengambil notifikasi"})
		return
	}

	c.JSON(http.StatusOK, gin.H{
		"status":      "success",
		"notifikasis": notifikasis,
	})
}

// MarkNotifikasiRead menandai notifikasi sebagai sudah dibaca
func MarkNotifikasiRead(c *gin.Context) {
	id := c.Param("id")

	var notifikasi models.Notifikasi
	if err := config.DB.First(&notifikasi, id).Error; err != nil {
		c.JSON(http.StatusNotFound, gin.H{"error": "Notifikasi tidak ditemukan"})
		return
	}

	now := time.Now()
	notifikasi.Status = "read"
	notifikasi.ReadAt = &now

	if err := config.DB.Save(&notifikasi).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal update notifikasi"})
		return
	}

	c.JSON(http.StatusOK, gin.H{
		"status":  "success",
		"message": "Notifikasi ditandai sebagai sudah dibaca",
		"data":    notifikasi,
	})
}
