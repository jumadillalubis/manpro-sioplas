package controllers

import (
	"SIOPLAS/config"
	"SIOPLAS/models"
	"net/http"
	"time"

	"github.com/gin-gonic/gin"
	"golang.org/x/crypto/bcrypt"
)

type ResetPWRequest struct {
	UserId      uint   `json:"user_id" binding:"required"`
	NewPassword string `json:"new_password" binding:"required"`
}

// ResetPassword mengubah password user
func ResetPassword(c *gin.Context) {
	var req ResetPWRequest

	if err := c.ShouldBindJSON(&req); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Input tidak valid"})
		return
	}

	// Cari user di tabel login
	var user models.Login
	if err := config.DB.First(&user, req.UserId).Error; err != nil {
		c.JSON(http.StatusNotFound, gin.H{"error": "User tidak ditemukan"})
		return
	}

	// Hash password baru
	hashedPW, err := bcrypt.GenerateFromPassword([]byte(req.NewPassword), bcrypt.DefaultCost)
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal meng-hash password"})
		return
	}

	// Update password di tabel login
	user.Password = string(hashedPW)
	user.LastLogout = time.Now()
	if err := config.DB.Save(&user).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal update password"})
		return
	}

	// Update password di tabel yang sesuai (Atasan, Katimja, atau Staff)
	// Password di tabel Atasan/Katimja/Staff disimpan sebagai plain text untuk konsistensi dengan sistem login
	// Cek di tabel Atasan berdasarkan email
	var atasan models.Atasan
	if err := config.DB.Where("email = ?", user.Email).First(&atasan).Error; err == nil {
		atasan.Password = req.NewPassword // Simpan password plain text untuk login
		if err := config.DB.Save(&atasan).Error; err != nil {
			c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal update password di tabel Atasan"})
			return
		}
	} else {
		// Cek di tabel Katimja berdasarkan email
		var katimja models.Katimja
		if err := config.DB.Where("email = ?", user.Email).First(&katimja).Error; err == nil {
			katimja.Password = req.NewPassword // Simpan password plain text untuk login
			if err := config.DB.Save(&katimja).Error; err != nil {
				c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal update password di tabel Katimja"})
				return
			}
		} else {
			// Cek di tabel Staff berdasarkan email
			var staff models.Staff
			if err := config.DB.Where("email = ?", user.Email).First(&staff).Error; err == nil {
				staff.Password = req.NewPassword // Simpan password plain text untuk login
				if err := config.DB.Save(&staff).Error; err != nil {
					c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal update password di tabel Staff"})
					return
				}
			} else {
				c.JSON(http.StatusNotFound, gin.H{"error": "User tidak ditemukan di tabel Atasan, Katimja, atau Staff"})
				return
			}
		}
	}

	// Simpan record reset password di tabel reset_passwords
	resetLog := models.ResetPW{
		UserId:      user.ID,
		Email:       user.Email,
		NewPassword: string(hashedPW),
		UpdatedAt:   time.Now(),
	}

	if err := config.DB.Create(&resetLog).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal menyimpan log reset password"})
		return
	}

	c.JSON(http.StatusOK, gin.H{
		"status":  "success",
		"message": "Password berhasil diubah dan dapat digunakan untuk login",
	})
}
