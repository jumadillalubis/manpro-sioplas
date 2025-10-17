package main

import (
	"net/http"
	"SIOPLAS/config"
	"SIOPLAS/models"

	"github.com/gin-gonic/gin"
)

func main() {
	// Koneksi ke database
	config.ConnectDatabase()

	// Inisialisasi router Gin
	r := gin.Default()

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
	r.GET("/api/login/id", func(c *gin.Context) {
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

	// ✅ Jalankan server
	r.Run(":8080") 
}
