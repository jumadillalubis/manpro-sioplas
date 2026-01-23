package controllers

import (
	"SIOPLAS/config"
	"SIOPLAS/models"
	"fmt"
	"net/http"
	"time"

	"github.com/gin-gonic/gin"
)

// CreateLaporan creates a new report and triggers notifications
func CreateLaporan(c *gin.Context) {
	var input models.Laporan
	if err := c.ShouldBindJSON(&input); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Input tidak valid"})
		return
	}

	if input.Tanggal.IsZero() {
		input.Tanggal = time.Now()
	}

	// Set status default if empty
	if input.Status == "" {
		input.Status = "Pending"
	}

	if err := config.DB.Create(&input).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal membuat laporan"})
		return
	}

	// Trigger Notifications asynchronously
	go func(laporanId uint, judul string) {
		// Notify Atasan
		var atasans []models.Atasan
		config.DB.Find(&atasans)
		for _, u := range atasans {
			CreateNotification(fmt.Sprint(laporanId), fmt.Sprintf("atasan-%d", u.Id), "Laporan Baru", fmt.Sprintf("Laporan '%s' telah masuk.", judul), "unread")
		}

		// Notify Katimja
		var katimjas []models.Katimja
		config.DB.Find(&katimjas)
		for _, u := range katimjas {
			CreateNotification(fmt.Sprint(laporanId), fmt.Sprintf("katimja-%d", u.Id), "Laporan Baru", fmt.Sprintf("Laporan '%s' telah masuk.", judul), "unread")
		}

		// Notify Staff
		var staffs []models.Staff
		config.DB.Find(&staffs)
		for _, u := range staffs {
			CreateNotification(fmt.Sprint(laporanId), fmt.Sprintf("staff-%d", u.Id), "Laporan Baru", fmt.Sprintf("Laporan '%s' telah masuk.", judul), "unread")
		}
	}(input.Id, input.Judul)

	c.JSON(http.StatusOK, gin.H{"status": "success", "message": "Laporan berhasil dibuat", "data": input})
}

// GetAllLaporan retrieves all reports
func GetAllLaporan(c *gin.Context) {
	var laporans []models.Laporan
	if err := config.DB.Find(&laporans).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal mengambil data laporan"})
		return
	}
	c.JSON(http.StatusOK, gin.H{"status": "success", "data": laporans})
}

// GetLaporanById retrieves a single report
func GetLaporanById(c *gin.Context) {
	id := c.Param("id")
	var laporan models.Laporan
	if err := config.DB.First(&laporan, id).Error; err != nil {
		c.JSON(http.StatusNotFound, gin.H{"error": "Laporan tidak ditemukan"})
		return
	}
	c.JSON(http.StatusOK, gin.H{"status": "success", "data": laporan})
}

// UpdateRingkasan updates the AI summary for a report
func UpdateRingkasan(c *gin.Context) {
	id := c.Param("id")
	var input struct {
		Ringkasan string `json:"ringkasan"`
	}
	if err := c.ShouldBindJSON(&input); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Input summarization tidak valid"})
		return
	}

	var laporan models.Laporan
	if err := config.DB.First(&laporan, id).Error; err != nil {
		c.JSON(http.StatusNotFound, gin.H{"error": "Laporan tidak ditemukan"})
		return
	}

	laporan.Ringkasan = input.Ringkasan
	if err := config.DB.Save(&laporan).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal menyimpan ringkasan"})
		return
	}

	c.JSON(http.StatusOK, gin.H{"status": "success", "message": "Ringkasan berhasil disimpan", "data": laporan})
}
