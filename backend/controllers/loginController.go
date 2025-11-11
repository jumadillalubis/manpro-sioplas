package controllers

import (
	"net/http"
	"time"
	"SIOPLAS/config"
	"SIOPLAS/models"

	"github.com/gin-gonic/gin"
)

// LoginRequest digunakan untuk menerima input login
type LoginRequest struct {
	Username string `json:"username" binding:"required"`
	Password string `json:"password" binding:"required"`
}

// Login endpoint login dan catat ke tabel logins
func Login(c *gin.Context) {
	var req LoginRequest
	if err := c.ShouldBindJSON(&req); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Input tidak valid"})
		return
	}

	var user models.Login

	// Cek di tabel Atasan
	var atasan models.Atasan
	if err := config.DB.Where("nama = ? AND password = ?", req.Username, req.Password).First(&atasan).Error; err == nil {
		user = models.Login{
			Nama:      atasan.Nama,
			Jabatan:   atasan.Jabatan,
			Email:     atasan.Email,
			LastLogin: time.Now(),
		}
		config.DB.Create(&user)

		c.JSON(http.StatusOK, gin.H{"status": "success", "message": "Login berhasil", "data": user})
		return
	}

	// Cek di tabel Katimja
	var katimja models.Katimja
	if err := config.DB.Where("nama = ? AND password = ?", req.Username, req.Password).First(&katimja).Error; err == nil {
		user = models.Login{
			Nama:      katimja.Nama,
			Jabatan:   katimja.Jabatan,
			Email:     katimja.Email,
			LastLogin: time.Now(),
		}
		config.DB.Create(&user)

		c.JSON(http.StatusOK, gin.H{"status": "success", "message": "Login berhasil", "data": user})
		return
	}

	// Cek di tabel Staff
	var staff models.Staff
	if err := config.DB.Where("nama = ? AND password = ?", req.Username, req.Password).First(&staff).Error; err == nil {
		user = models.Login{
			Nama:      staff.Nama,
			Jabatan:   staff.Jabatan,
			Email:     staff.Email,
			LastLogin: time.Now(),
		}
		config.DB.Create(&user)

		c.JSON(http.StatusOK, gin.H{"status": "success", "message": "Login berhasil", "data": user})
		return
	}

	// Jika tidak ketemu
	c.JSON(http.StatusUnauthorized, gin.H{"status": "error", "message": "Username atau password salah"})
}
