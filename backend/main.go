package main

import (
	"SIOPLAS/config"
	"SIOPLAS/controllers"
	"SIOPLAS/models"
	"net/http"

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

	// ✅ Endpoint POST login untuk semua role (Atasan, Katimja, Staff)
	// Mencari data yang sudah ada di database SIOPLAS (tidak membuat data baru)
	r.POST("/api/login", controllers.Login)

	// ✅ Endpoint GET semua data login (untuk keperluan lain)
	r.GET("/api/logins", func(c *gin.Context) {
		var logins []models.Login
		config.DB.Find(&logins)
		c.JSON(http.StatusOK, gin.H{
			"status": "success",
			"data":   logins,
		})
	})

	// ✅ Endpoint GET login berdasarkan ID
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

	// ✅ Endpoint POST login atasan (untuk backward compatibility)
	// Mencari data yang sudah ada di tabel atasans (tidak membuat data baru)
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
		// Hanya membaca dari database SIOPLAS, tidak membuat data baru
		if err := config.DB.Where("nama = ? AND password = ?", input.Username, input.Password).First(&atasan).Error; err != nil {
			c.JSON(http.StatusUnauthorized, gin.H{"error": "Username atau password salah. Pastikan data sudah ada di database SIOPLAS."})
			return
		}

		c.JSON(http.StatusOK, gin.H{
			"status":  "success",
			"message": "Login berhasil",
			"jabatan": atasan.Jabatan, // Jabatan dari database SIOPLAS
			"data":    atasan,
		})
	})

	// ✅ Endpoint GET data atasan berdasarkan ID
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

	// ✅ Endpoint GET semua data katimja (untuk debugging)
	r.GET("/api/katimja", func(c *gin.Context) {
		var katimjas []models.Katimja

		// Cek apakah tabel ada
		if !config.DB.Migrator().HasTable(&models.Katimja{}) {
			c.JSON(http.StatusOK, gin.H{
				"status":  "info",
				"message": "Tabel katimjas belum ada atau tidak ditemukan",
				"count":   0,
				"data":    []models.Katimja{},
			})
			return
		}

		if err := config.DB.Find(&katimjas).Error; err != nil {
			c.JSON(http.StatusInternalServerError, gin.H{
				"error":   err.Error(),
				"message": "Error saat membaca data dari tabel katimjas",
			})
			return
		}

		c.JSON(http.StatusOK, gin.H{
			"status":  "success",
			"message": "Data berhasil diambil dari tabel katimjas",
			"count":   len(katimjas),
			"data":    katimjas,
		})
	})

	// ✅ Endpoint GET data katimja berdasarkan ID
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

	// ✅ Jalankan server
	r.Run(":8080")
}
