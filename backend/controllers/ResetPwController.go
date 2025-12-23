package controllers

import (
	"net/http"
	"time"
	"SIOPLAS/config"
	"SIOPLAS/models"

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
	user.LastLogout = time.Now() // optional: catat waktu reset
	if err := config.DB.Save(&user).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal update password"})
		return
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
		"message": "Password berhasil diubah",
	})
}
