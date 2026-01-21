package controllers

import (
	"SIOPLAS/config"
	"SIOPLAS/models"
	"fmt"
	"net/http"
	"time"

	"github.com/gin-gonic/gin"
)

func ResetPW (c *gin.Context) {
	var input struct {
		Name        string `json:"name"`
		NewPassword string `json:"new_password"`
	}

	if err := c.ShouldBindJSON(&input); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Input tidak valid"})
		return
	}

	var atasan models.Atasan
	var katimja models.Katimja
	var staff models.Staff
	var userID uint
	var role string
	var email string

	// Cari user di tabel Atasan
	if err := config.DB.Where("nama = ?", input.Name).First(&atasan).Error; err == nil {
		userID = atasan.Id
		role = "atasan"
		email = atasan.Email // Asumsi ada field Email

		// Update password
		atasan.Password = input.NewPassword
		if err := config.DB.Save(&atasan).Error; err != nil {
			c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal update password Atasan"})
			return
		}
	} else if err := config.DB.Where("nama = ?", input.Name).First(&katimja).Error; err == nil {
		userID = katimja.Id
		role = "katimja"
		email = katimja.Email

		// Update password
		katimja.Password = input.NewPassword
		if err := config.DB.Save(&katimja).Error; err != nil {
			c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal update password Katimja"})
			return
		}
	} else if err := config.DB.Where("nama = ?", input.Name).First(&staff).Error; err == nil {
		userID = staff.Id
		role = "staff"
		email = staff.Email

		// Update password
		staff.Password = input.NewPassword
		if err := config.DB.Save(&staff).Error; err != nil {
			c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal update password Staff"})
			return
		}
	} else {
		c.JSON(http.StatusNotFound, gin.H{"error": "User tidak ditemukan"})
		return
	}

	// Catat ke tabel reset_passwords
	resetLog := models.ResetPW{
		UserId:      userID,
		Email:       email,
		NewPassword: input.NewPassword,
		UpdatedAt:   time.Now(),
	}

	if err := config.DB.Create(&resetLog).Error; err != nil {
		fmt.Printf("⚠️ Gagal mencatat log reset password: %v\n", err)
	}

	c.JSON(http.StatusOK, gin.H{
		"status":  "success",
		"message": "Password berhasil direset",
		"role":    role,
	})
}
