package main

import (
	"SIOPLAS/config"
	"SIOPLAS/controllers"
	"SIOPLAS/models"
	"net/http"
	"time"

	"github.com/gin-gonic/gin"
)

func main() {
	// Koneksi ke database
	config.ConnectDatabase()

	// Inisialisasi router Gin
	r := gin.Default()

	// Setup CORS manual
	r.Use(func(c *gin.Context) {
		c.Header("Access-Control-Allow-Origin", "*")
		c.Header("Access-Control-Allow-Methods", "GET, POST, PUT, DELETE, OPTIONS")
		c.Header("Access-Control-Allow-Headers", "Origin, Content-Type, Accept, Authorization")
		c.Header("Access-Control-Allow-Credentials", "true")

		if c.Request.Method == "OPTIONS" {
			c.AbortWithStatus(204)
			return
		}

		c.Next()
	})

	// -------------------------
	// LOGIN
	// -------------------------
	r.POST("/api/login", controllers.Login)
	r.POST("/api/logout", controllers.Logout)

	// -------------------------
	// GET Semua login
	// -------------------------
	r.GET("/api/logins", func(c *gin.Context) {
		var logins []models.Login

		// Filter berdasarkan jabatan jika ada query parameter
		jabatan := c.Query("jabatan")
		query := config.DB

		if jabatan != "" {
			query = query.Where("jabatan LIKE ?", "%"+jabatan+"%")
		}

		// Urutkan berdasarkan LastLogin terbaru (DESC) dan ID terbaru untuk memastikan data terbaru
		// Gunakan fresh query tanpa cache untuk memastikan data real-time
		if err := query.Order("last_login DESC, id DESC").Find(&logins).Error; err != nil {
			c.JSON(http.StatusInternalServerError, gin.H{"status": "error", "message": err.Error()})
			return
		}

		c.JSON(http.StatusOK, gin.H{
			"status": "success",
			"data":   logins,
		})
	})

	// GET login berdasarkan ID
	r.GET("/api/login/:id", func(c *gin.Context) {
		var login models.Login
		id := c.Param("id")

		if err := config.DB.First(&login, id).Error; err != nil {
			c.JSON(http.StatusNotFound, gin.H{"error": "Data tidak ditemukan"})
			return
		}
		c.JSON(http.StatusOK, gin.H{
			"status": "success",
			"data":   login,
		})
	})

	// -------------------------
	// Atasan
	// -------------------------
	r.POST("/api/atasan/login", func(c *gin.Context) {
		var input struct {
			Username string `json:"username"`
			Password string `json:"password"`
		}

		if err := c.ShouldBindJSON(&input); err != nil {
			c.JSON(http.StatusBadRequest, gin.H{"error": "Input tidak valid"})
			return
		}

		var atasan models.Atasan
		if err := config.DB.Where("nama = ? AND password = ?", input.Username, input.Password).First(&atasan).Error; err != nil {
			c.JSON(http.StatusUnauthorized, gin.H{"error": "Username atau password salah"})
			return
		}

		c.JSON(http.StatusOK, gin.H{
			"status":  "success",
			"message": "Login berhasil",
			"jabatan": atasan.Jabatan,
			"data":    atasan,
		})
	})

	r.GET("/api/atasan/:id", func(c *gin.Context) {
		var atasan models.Atasan
		id := c.Param("id")
		if err := config.DB.First(&atasan, id).Error; err != nil {
			c.JSON(http.StatusNotFound, gin.H{"error": "Data atasan tidak ditemukan"})
			return
		}
		c.JSON(http.StatusOK, gin.H{
			"status": "success",
			"data":   atasan,
		})
	})

	// -------------------------
	// Katimja
	// -------------------------
	r.GET("/api/katimja", func(c *gin.Context) {
		var katimjas []models.Katimja
		if err := config.DB.Find(&katimjas).Error; err != nil {
			c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
			return
		}
		c.JSON(http.StatusOK, gin.H{
			"status": "success",
			"data":   katimjas,
		})
	})

	r.GET("/api/katimja/:id", func(c *gin.Context) {
		var katimja models.Katimja
		id := c.Param("id")
		if err := config.DB.First(&katimja, id).Error; err != nil {
			c.JSON(http.StatusNotFound, gin.H{"error": "Data katimja tidak ditemukan"})
			return
		}
		c.JSON(http.StatusOK, gin.H{
			"status": "success",
			"data":   katimja,
		})
	})

	r.GET("/api/staff", func(c *gin.Context) {
		var staffs []models.Staff
		if err := config.DB.Find(&staffs).Error; err != nil {
			c.JSON(http.StatusInternalServerError, gin.H{"error": err.Error()})
			return
		}
		c.JSON(http.StatusOK, gin.H{
			"status": "success",
			"data":   staffs,
		})
	})

	r.GET("/api/staff/:id", func(c *gin.Context) {
		var staff models.Staff
		id := c.Param("id")
		if err := config.DB.First(&staff, id).Error; err != nil {
			c.JSON(http.StatusNotFound, gin.H{"error": "Data staff tidak ditemukan"})
			return
		}
		c.JSON(http.StatusOK, gin.H{
			"status": "success",
			"data":   staff,
		})
	})

	// -------------------------
	// CRUD Reset Password
	// -------------------------
	r.POST("/api/reset-pw", controllers.ResetPassword)

	r.GET("/api/reset-pw", func(c *gin.Context) {
		var logs []models.ResetPW
		config.DB.Find(&logs)
		c.JSON(http.StatusOK, gin.H{"status": "success", "data": logs})
	})

	r.GET("/api/reset-pw/:id", func(c *gin.Context) {
		var log models.ResetPW
		id := c.Param("id")
		if err := config.DB.First(&log, id).Error; err != nil {
			c.JSON(http.StatusNotFound, gin.H{"error": "Data tidak ditemukan"})
			return
		}
		c.JSON(http.StatusOK, gin.H{"status": "success", "data": log})
	})

	r.PUT("/api/reset-pw/:id", func(c *gin.Context) {
		var log models.ResetPW
		id := c.Param("id")
		if err := config.DB.First(&log, id).Error; err != nil {
			c.JSON(http.StatusNotFound, gin.H{"error": "Data tidak ditemukan"})
			return
		}

		var input struct {
			NewPassword string `json:"new_password"`
		}
		if err := c.ShouldBindJSON(&input); err != nil {
			c.JSON(http.StatusBadRequest, gin.H{"error": "Input tidak valid"})
			return
		}

		log.NewPassword = input.NewPassword
		log.UpdatedAt = time.Now()
		config.DB.Save(&log)

		c.JSON(http.StatusOK, gin.H{"status": "success", "data": log})
	})

	r.DELETE("/api/reset-pw/:id", func(c *gin.Context) {
		id := c.Param("id")
		if err := config.DB.Delete(&models.ResetPW{}, id).Error; err != nil {
			c.JSON(http.StatusInternalServerError, gin.H{"error": "Gagal menghapus data"})
			return
		}
		c.JSON(http.StatusOK, gin.H{"status": "success", "message": "Data berhasil dihapus"})
	})

	// -------------------------
	// CRUD Tugas (Atasan membuat tugas, mengirim ke Katimja/Staff)
	// -------------------------
	// Atasan membuat tugas dan mengirim notifikasi
	r.POST("/api/tugas", controllers.CreateTugas)

	// Get semua tugas
	r.GET("/api/tugas", controllers.GetAllTugas)

	// Get tugas berdasarkan ID
	r.GET("/api/tugas/:id", controllers.GetTugasByID)

	// Get tugas berdasarkan pembuat (atasan)
	r.GET("/api/tugas/pembuat/:pembuat", controllers.GetTugasByPembuat)

	// Get tugas berdasarkan penerima (katimja/staff)
	r.GET("/api/tugas/penerima/:penerima_id", controllers.GetTugasByPenerima)

	// Update status tugas oleh penerima
	r.PUT("/api/tugas/penerima/:id/status", controllers.UpdateStatusTugasPenerima)

	// -------------------------
	// Notifikasi
	// -------------------------
	// Get notifikasi berdasarkan penerima
	r.GET("/api/notifikasi/penerima/:penerima_id", controllers.GetNotifikasiByPenerima)

	// Mark notifikasi sebagai sudah dibaca
	r.PUT("/api/notifikasi/:id/read", controllers.MarkNotifikasiRead)

	// -------------------------
	// Jalankan server
	// -------------------------
	r.Run(":8080")
}
