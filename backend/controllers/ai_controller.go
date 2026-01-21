package controllers

import (
	"net/http"

	"github.com/gin-gonic/gin"
)

func SummarizePDF(c *gin.Context) {
	file, err := c.FormFile("file")
	if err != nil {
		c.JSON(http.StatusBadRequest, gin.H{
			"status": "error",
			"message": "PDF file is required",
		})
		return
	}

	c.JSON(http.StatusOK, gin.H{
		"status":   "success",
		"filename": file.Filename,
		"summary":  "Ini hasil ringkasan AI (dummy)",
	})
}
