package controllers

import (
	"SIOPLAS/config"
	"SIOPLAS/models"
	"net/http"

	"github.com/gin-gonic/gin"
)

// Login - Mencari data yang sudah ada di database SIOPLAS
// Tidak membuat data baru, hanya membaca dari tabel: atasans, katimjas, staffs
func Login(c *gin.Context) {
	var input struct {
		Username string `json:"username"`
		Password string `json:"password"`
	}

	if err := c.ShouldBindJSON(&input); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Input tidak valid"})
		return
	}

	var atasan models.Atasan
	var katimja models.Katimja
	var staff models.Staff

	// Cari di tabel atasans (data yang sudah ada di database SIOPLAS)
	// Hanya membaca, tidak membuat data baru
	if err := config.DB.Where("nama = ? AND password = ?", input.Username, input.Password).First(&atasan).Error; err == nil {
		c.JSON(http.StatusOK, gin.H{
			"status":  "success",
			"message": "Login berhasil",
			"jabatan": atasan.Jabatan, // Jabatan dari database SIOPLAS
			"data":    atasan,
		})
		return
	}

	// Cari di tabel katimjas (data yang sudah ada di database SIOPLAS)
	// Login hanya menggunakan nama dan password (tidak menggunakan email)
	// Hanya membaca, tidak membuat data baru
	err := config.DB.Where("nama = ? AND password = ?", input.Username, input.Password).First(&katimja).Error
	if err == nil {
		c.JSON(http.StatusOK, gin.H{
			"status":  "success",
			"message": "Login berhasil",
			"jabatan": katimja.Jabatan, // Jabatan dari database SIOPLAS
			"data":    katimja,
		})
		return
	}
	// Jika tidak ditemukan di katimjas, lanjut ke tabel staffs

	// Cari di tabel staffs (data yang sudah ada di database SIOPLAS)
	// Hanya membaca, tidak membuat data baru
	if err := config.DB.Where("nama = ? AND password = ?", input.Username, input.Password).First(&staff).Error; err == nil {
		c.JSON(http.StatusOK, gin.H{
			"status":  "success",
			"message": "Login berhasil",
			"jabatan": staff.Jabatan, // Jabatan dari database SIOPLAS
			"data":    staff,
		})
		return
	}

	// Jika tidak ditemukan di ketiga tabel (data tidak ada di database SIOPLAS)
	c.JSON(http.StatusUnauthorized, gin.H{
		"status": "error",
		"error":  "Username atau password salah. Pastikan data sudah ada di database SIOPLAS.",
	})
}
