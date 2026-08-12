package controllers

import (
	"SIOPLAS/config"
	"SIOPLAS/models"
	"net/http"
	"time"

	"github.com/gin-gonic/gin"
)

type NoteInput struct {
	Email   string `json:"email" binding:"required"`
	Content string `json:"content"`
}

// GetUserNote handles GET /api/note?email=xxx
func GetUserNote(c *gin.Context) {
	email := c.Query("email")
	if email == "" {
		c.JSON(http.StatusBadRequest, gin.H{"status": "error", "message": "Parameter email wajib diisi"})
		return
	}

	var note models.UserNote
	if err := config.DB.Where("user_email = ?", email).First(&note).Error; err != nil {
		c.JSON(http.StatusOK, gin.H{
			"status":  "success",
			"content": "",
		})
		return
	}

	c.JSON(http.StatusOK, gin.H{
		"status":     "success",
		"content":    note.Content,
		"updated_at": note.UpdatedAt,
	})
}

// SaveUserNote handles POST /api/note
func SaveUserNote(c *gin.Context) {
	var input NoteInput
	if err := c.ShouldBindJSON(&input); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"status": "error", "message": "Input tidak valid"})
		return
	}

	var note models.UserNote
	result := config.DB.Where("user_email = ?", input.Email).First(&note)

	if result.Error != nil {
		// New note entry
		note = models.UserNote{
			UserEmail: input.Email,
			Content:   input.Content,
			UpdatedAt: time.Now(),
		}
		if err := config.DB.Create(&note).Error; err != nil {
			c.JSON(http.StatusInternalServerError, gin.H{"status": "error", "message": "Gagal menyimpan catatan"})
			return
		}
	} else {
		// Update existing note
		note.Content = input.Content
		note.UpdatedAt = time.Now()
		if err := config.DB.Save(&note).Error; err != nil {
			c.JSON(http.StatusInternalServerError, gin.H{"status": "error", "message": "Gagal meng-update catatan"})
			return
		}
	}

	c.JSON(http.StatusOK, gin.H{
		"status":  "success",
		"message": "Catatan berhasil disimpan",
		"content": note.Content,
	})
}
