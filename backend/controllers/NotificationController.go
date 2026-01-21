package controllers

import (
	"SIOPLAS/config"
	"SIOPLAS/models"
	"net/http"
	"time"

	"github.com/gin-gonic/gin"
)

// CreateNotification adds a new notification to the database
func CreateNotification(tugasId, penerimaId, judul, pesan, status string) error {
	notification := models.Notification{
		TugasId:    tugasId,
		PenerimaId: penerimaId,
		Judul:      judul,
		Pesan:      pesan,
		Status:     status,
		CreatedAt:  time.Now(),
		UpdatedAt:  time.Now(),
	}

	if err := config.DB.Create(&notification).Error; err != nil {
		return err
	}
	return nil
}

// GetUserNotifications fetches notifications for a specific user ID/Role
func GetUserNotifications(c *gin.Context) {
	penerimaId := c.Query("penerima_id") // Expect formatted ID like "staff-1"

	var notifications []models.Notification
	query := config.DB.Order("created_at desc")
	
	if penerimaId != "" {
		query = query.Where("penerima_id = ?", penerimaId)
	}

	if err := query.Find(&notifications).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal mengambil notifikasi"})
		return
	}

	c.JSON(http.StatusOK, gin.H{
		"status": "success",
		"data":   notifications,
	})
}

// MarkAsRead updates the read status
func MarkAsRead(c *gin.Context) {
	id := c.Param("id")
	var notification models.Notification

	if err := config.DB.First(&notification, id).Error; err != nil {
		c.JSON(http.StatusNotFound, gin.H{"error": "Notifikasi tidak ditemukan"})
		return
	}

	notification.ReadAt = time.Now()
	config.DB.Save(&notification)

	c.JSON(http.StatusOK, gin.H{"status": "success", "message": "Notifikasi telah dibaca"})
}
