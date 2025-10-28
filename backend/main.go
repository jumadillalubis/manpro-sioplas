package main

import (
	"SIOPLAS/config"
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

	// ✅ Endpoint GET semua data login
	r.GET("/api/login", func(c *gin.Context) {
		var logins []models.Login
		config.DB.Find(&logins)
		c.JSON(http.StatusOK, gin.H{
			"status": "success",
			"data":   logins,
		})
	})

	// ✅ Endpoint POST tambah data login
	r.POST("/api/login", func(c *gin.Context) {
		var login models.Login
		if err := c.ShouldBindJSON(&login); err != nil {
			c.JSON(http.StatusBadRequest, gin.H{"error": err.Error()})
			return
		}
		config.DB.Create(&login)
		c.JSON(http.StatusOK, gin.H{
			"status": "success",
			"data":   login,
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

	// ✅ Endpoint POST login atasan
	r.POST("/api/atasan/login", func(c *gin.Context) {
		var input struct {
			Email    string `json:"email"`
			Password string `json:"password"`
		}

		if err := c.ShouldBindJSON(&input); err != nil {
			c.JSON(http.StatusBadRequest, gin.H{"error": "Input tidak valid"})
			return
		}

		var atasan models.Atasan
		if err := config.DB.Where("email = ? AND password = ?", input.Email, input.Password).First(&atasan).Error; err != nil {
			c.JSON(http.StatusUnauthorized, gin.H{"error": "Email atau password salah"})
			return
		}

		c.JSON(http.StatusOK, gin.H{
			"status":  "success",
			"message": "Login berhasil",
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

	// ✅ Jalankan server
	r.Run(":8081")
}
