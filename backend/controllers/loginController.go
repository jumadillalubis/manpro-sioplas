package controllers

import (
	"net/http"
	"SIOPLAS/config"
	"SIOPLAS/models"

	"github.com/gin-gonic/gin"
)

func Login(c *gin.Context) {
	var input struct {
		Email    string `json:"email"`
		Password string `json:"password"`
	}
	if err := c.ShouldBindJSON(&input); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Input tidak valid"})
		return
	}

	var atasan models.Atasan
	var kaptimja models.Katimja
	var staff models.Staff

	// tabel atasan
	if err := config.DB.Where("email = ? AND password = ?", input.Email, input.Password).First(&atasan).Error; err == nil {
		c.JSON(http.StatusOK, gin.H{
			"message": "Login berhasil",
			"user":    atasan,
			"role":    "Atasan",
		})
		return
	}

	// tabel kaptimja
	if err := config.DB.Where("email = ? AND password = ?", input.Email, input.Password).First(&kaptimja).Error; err == nil {
		c.JSON(http.StatusOK, gin.H{
			"message": "Login berhasil",
			"user":    kaptimja,
			"role":    "Kaptimja",
		})
		return
	}

	// tabel staff
	if err := config.DB.Where("email = ? AND password = ?", input.Email, input.Password).First(&staff).Error; err == nil {
		c.JSON(http.StatusOK, gin.H{
			"message": "Login berhasil",
			"user":    staff,
			"role":    "Staff",
		})
		return
	}

	// jika semua gagal
	c.JSON(http.StatusUnauthorized, gin.H{"error": "Email atau password salah"})
}
