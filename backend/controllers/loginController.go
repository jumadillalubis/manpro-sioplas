package controllers

import (
	"SIOPLAS/config"
	"SIOPLAS/models"
	"net/http"
	"time"

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
		// Cek apakah user sudah ada di tabel Login berdasarkan email
		if err := config.DB.Where("email = ?", atasan.Email).First(&user).Error; err != nil {
			// Jika belum ada, buat record baru
		user = models.Login{
			Nama:      atasan.Nama,
			Jabatan:   atasan.Jabatan,
			Email:     atasan.Email,
				Nip:       atasan.Nip,
			LastLogin: time.Now(),
		}
		config.DB.Create(&user)
		} else {
			// Jika sudah ada, update semua field untuk sinkronisasi data dengan waktu sekarang
			now := time.Now()
			updateData := map[string]interface{}{
				"nama":       atasan.Nama,
				"jabatan":    atasan.Jabatan,
				"nip":        atasan.Nip,
				"last_login": now,
			}
			if err := config.DB.Model(&user).Updates(updateData).Error; err != nil {
				c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal update data login"})
				return
			}
			// Reload data dari database untuk memastikan data terbaru
			config.DB.First(&user, user.ID)
		}

		c.JSON(http.StatusOK, gin.H{
			"status":  "success",
			"message": "Login berhasil",
			"jabatan": user.Jabatan,
			"data":    user,
		})
		return
	}

	// Cek di tabel Katimja
	var katimja models.Katimja
	if err := config.DB.Where("nama = ? AND password = ?", req.Username, req.Password).First(&katimja).Error; err == nil {
		// Cek apakah user sudah ada di tabel Login berdasarkan email
		if err := config.DB.Where("email = ?", katimja.Email).First(&user).Error; err != nil {
			// Jika belum ada, buat record baru
		user = models.Login{
			Nama:      katimja.Nama,
			Jabatan:   katimja.Jabatan,
			Email:     katimja.Email,
				Nip:       katimja.Nip,
			LastLogin: time.Now(),
		}
		config.DB.Create(&user)
		} else {
			// Jika sudah ada, update semua field untuk sinkronisasi data dengan waktu sekarang
			now := time.Now()
			updateData := map[string]interface{}{
				"nama":       katimja.Nama,
				"jabatan":    katimja.Jabatan,
				"nip":        katimja.Nip,
				"last_login": now,
			}
			if err := config.DB.Model(&user).Updates(updateData).Error; err != nil {
				c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal update data login"})
				return
			}
			// Reload data dari database untuk memastikan data terbaru
			config.DB.First(&user, user.ID)
		}

		c.JSON(http.StatusOK, gin.H{
			"status":  "success",
			"message": "Login berhasil",
			"jabatan": user.Jabatan,
			"data":    user,
		})
		return
	}

	// Cek di tabel Staff
	var staff models.Staff
	if err := config.DB.Where("nama = ? AND password = ?", req.Username, req.Password).First(&staff).Error; err == nil {
		// Cek apakah user sudah ada di tabel Login berdasarkan email
		if err := config.DB.Where("email = ?", staff.Email).First(&user).Error; err != nil {
			// Jika belum ada, buat record baru
		user = models.Login{
			Nama:      staff.Nama,
			Jabatan:   staff.Jabatan,
			Email:     staff.Email,
				Nup:       staff.Nup,
			LastLogin: time.Now(),
		}
		config.DB.Create(&user)
		} else {
			// Jika sudah ada, update semua field untuk sinkronisasi data dengan waktu sekarang
			now := time.Now()
			updateData := map[string]interface{}{
				"nama":       staff.Nama,
				"jabatan":    staff.Jabatan,
				"nup":        staff.Nup,
				"last_login": now,
			}
			if err := config.DB.Model(&user).Updates(updateData).Error; err != nil {
				c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal update data login"})
				return
			}
			// Reload data dari database untuk memastikan data terbaru
			config.DB.First(&user, user.ID)
		}

		c.JSON(http.StatusOK, gin.H{
			"status":  "success",
			"message": "Login berhasil",
			"jabatan": user.Jabatan,
			"data":    user,
		})
		return
	}

	// Jika tidak ketemu
	c.JSON(http.StatusUnauthorized, gin.H{"status": "error", "message": "Username atau password salah"})
}

// LogoutRequest digunakan untuk menerima input logout
type LogoutRequest struct {
	Email string `json:"email" binding:"required"`
}

// Logout endpoint logout dan update LastLogout
func Logout(c *gin.Context) {
	var req LogoutRequest
	if err := c.ShouldBindJSON(&req); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Input tidak valid"})
		return
	}

	// Cari user di tabel Login berdasarkan email
	var user models.Login
	if err := config.DB.Where("email = ?", req.Email).First(&user).Error; err != nil {
		c.JSON(http.StatusNotFound, gin.H{"error": "User tidak ditemukan"})
		return
	}

	// Update LastLogout dengan waktu sekarang
	now := time.Now()
	updateData := map[string]interface{}{
		"last_logout": now,
	}
	if err := config.DB.Model(&user).Updates(updateData).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal update logout time"})
		return
	}
	// Reload data dari database untuk memastikan data terbaru
	config.DB.First(&user, user.ID)

	c.JSON(http.StatusOK, gin.H{
		"status":  "success",
		"message": "Logout berhasil",
		"data":    user,
	})
}
