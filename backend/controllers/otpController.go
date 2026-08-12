package controllers

import (
	"SIOPLAS/config"
	"SIOPLAS/models"
	"net/http"
	"time"

	"github.com/gin-gonic/gin"
)

// VerifyOTPRequest adalah struktur input untuk verifikasi OTP
type VerifyOTPRequest struct {
	Email string `json:"email" binding:"required"`
	Code  string `json:"code" binding:"required"`
}

// VerifyOTP memverifikasi kode OTP yang dikirim oleh pengguna
func VerifyOTP(c *gin.Context) {
	var req VerifyOTPRequest
	if err := c.ShouldBindJSON(&req); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{
			"status":  "error",
			"message": "Input tidak valid. Pastikan email dan kode OTP diisi.",
		})
		return
	}

	// Cari OTP terbaru yang belum dipakai untuk email ini
	var otp models.OTP
	err := config.DB.
		Where("email = ? AND code = ? AND used = ? AND expired_at > ?",
			req.Email, req.Code, false, time.Now()).
		Order("created_at DESC").
		First(&otp).Error

	if err != nil {
		// Berikan pesan error yang lebih spesifik
		// Cek apakah OTP sudah expired
		var expiredOTP models.OTP
		if config.DB.Where("email = ? AND code = ? AND used = ?", req.Email, req.Code, false).
			First(&expiredOTP).Error == nil {
			// OTP ditemukan tapi sudah expired
			c.JSON(http.StatusUnauthorized, gin.H{
				"status":  "error",
				"message": "Kode OTP telah kedaluwarsa. Silakan login ulang untuk mendapatkan kode baru.",
			})
			return
		}

		// OTP tidak ditemukan atau sudah digunakan
		c.JSON(http.StatusUnauthorized, gin.H{
			"status":  "error",
			"message": "Kode OTP tidak valid atau sudah digunakan.",
		})
		return
	}

	// Tandai OTP sebagai sudah digunakan
	config.DB.Model(&otp).Update("used", true)

	c.JSON(http.StatusOK, gin.H{
		"status":  "success",
		"message": "Verifikasi OTP berhasil.",
	})
}
