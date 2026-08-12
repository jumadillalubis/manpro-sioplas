package controllers

import (
	"SIOPLAS/config"
	"SIOPLAS/models"
	"SIOPLAS/service"
	"fmt"
	"math/rand"
	"net/http"
	"strings"
	"time"

	"github.com/gin-gonic/gin"
)

// LoginRequest digunakan untuk menerima input login
type LoginRequest struct {
	Username string `json:"username" binding:"required"`
	Password string `json:"password" binding:"required"`
}

// generateOTPCode membuat kode OTP acak 6-digit
func generateOTPCode() string {
	r := rand.New(rand.NewSource(time.Now().UnixNano()))
	return fmt.Sprintf("%06d", r.Intn(1000000))
}

// maskEmail menyamarkan sebagian alamat email (contoh: "u***@gmail.com")
func maskEmail(email string) string {
	parts := strings.Split(email, "@")
	if len(parts) != 2 {
		return email
	}
	local := parts[0]
	if len(local) <= 1 {
		return email
	}
	masked := string(local[0]) + "***"
	return masked + "@" + parts[1]
}

// Login adalah endpoint login tahap 1:
// Verifikasi Username & Password → generate OTP → kirim ke email → return otp_sent
func Login(c *gin.Context) {
	var req LoginRequest
	if err := c.ShouldBindJSON(&req); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Input tidak valid"})
		return
	}

	var (
		userEmail  string
		userName   string
		userJabatan string
		userId     uint
		userDivisi string
		userData   interface{}
	)

	// Cek di tabel Atasan
	var atasan models.Atasan
	if err := config.DB.Where("nama = ? AND password = ?", req.Username, req.Password).First(&atasan).Error; err == nil {
		userEmail   = atasan.Email
		userName    = atasan.Nama
		userJabatan = atasan.Jabatan
		userId      = atasan.Id
		userDivisi  = ""
		userData    = atasan
		goto sendOTP
	}

	// Cek di tabel Katimja
	{
		var katimja models.Katimja
		if err := config.DB.Where("nama = ? AND password = ?", req.Username, req.Password).First(&katimja).Error; err == nil {
			userEmail   = katimja.Email
			userName    = katimja.Nama
			userJabatan = katimja.Jabatan
			userId      = katimja.Id
			userDivisi  = katimja.Divisi
			userData    = katimja
			goto sendOTP
		}
	}

	// Cek di tabel Staff
	{
		var staff models.Staff
		if err := config.DB.Where("nama = ? AND password = ?", req.Username, req.Password).First(&staff).Error; err == nil {
			userEmail   = staff.Email
			userName    = staff.Nama
			userJabatan = staff.Jabatan
			userId      = staff.Id
			userDivisi  = staff.Divisi
			userData    = staff
			goto sendOTP
		}
	}

	// Jika tidak ketemu
	c.JSON(http.StatusUnauthorized, gin.H{
		"status":  "error",
		"message": "Username atau password salah",
	})
	return

sendOTP:
	// Catat ke tabel logins
	loginRecord := models.Login{
		Nama:      userName,
		Jabatan:   userJabatan,
		Email:     userEmail,
		LastLogin: time.Now(),
	}
	config.DB.Where(models.Login{Email: userEmail}).Assign(loginRecord).FirstOrCreate(&loginRecord)

	// Generate OTP 6-digit
	otpCode := generateOTPCode()
	expiredAt := time.Now().Add(5 * time.Minute)

	// Hapus OTP lama yang belum dipakai untuk email ini (cleanup)
	config.DB.Where("email = ? AND used = ?", userEmail, false).Delete(&models.OTP{})

	// Simpan OTP baru ke database
	newOTP := models.OTP{
		Email:     userEmail,
		Code:      otpCode,
		ExpiredAt: expiredAt,
		Used:      false,
	}
	if err := config.DB.Create(&newOTP).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{
			"status":  "error",
			"message": "Gagal membuat kode OTP. Silakan coba lagi.",
		})
		return
	}

	// Kirim OTP ke email via Mailtrap
	if err := service.SendOTPEmail(userEmail, otpCode, userName); err != nil {
		// Tetap lanjut, tapi log errornya
		fmt.Printf("⚠️  Gagal kirim email OTP ke %s: %v\n", userEmail, err)
		// Untuk development, cetak OTP ke console agar bisa ditest tanpa email
		fmt.Printf("🔑 [DEV] OTP untuk %s: %s\n", userEmail, otpCode)
	}

	// Kembalikan response otp_sent (data user dikembalikan untuk disimpan sementara di Laravel)
	c.JSON(http.StatusOK, gin.H{
		"status":       "otp_sent",
		"message":      "Kode OTP telah dikirimkan ke email Anda.",
		"email_masked": maskEmail(userEmail),
		"email":        userEmail,
		"user_id":      userId,
		"user_jabatan": userJabatan,
		"user_divisi":  userDivisi,
		"data":         userData,
	})
}
