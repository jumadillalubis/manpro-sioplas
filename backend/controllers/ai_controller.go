package controllers

import (
	"SIOPLAS/config"
	"SIOPLAS/models"
	"SIOPLAS/service"
	"fmt"
	"log"
	"net/http"
	"os"
	"path/filepath"

	"github.com/gin-gonic/gin"
)

type SummarizeRequest struct {
	IndikatorID int    `json:"indikator_id" binding:"required"`
	Triwulan    string `json:"triwulan" binding:"required"`
}

func SummarizePDF(c *gin.Context) {
	var input SummarizeRequest
	if err := c.ShouldBindJSON(&input); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{
			"status":  "error",
			"message": "Input tidak valid: " + err.Error(),
		})
		return
	}

	log.Printf("📝 AI Summarize request: Indikator %d, Triwulan %s", input.IndikatorID, input.Triwulan)

	// Get all laporan with lampiran for this indikator + triwulan
	var laporans []models.Laporan
	if err := config.DB.Where("indikator_id = ? AND triwulan = ? AND lampiran IS NOT NULL AND lampiran != ''",
		input.IndikatorID, input.Triwulan).Find(&laporans).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{
			"status":  "error",
			"message": "Gagal mengambil data laporan: " + err.Error(),
		})
		return
	}

	if len(laporans) == 0 {
		c.JSON(http.StatusNotFound, gin.H{
			"status":  "error",
			"message": "Tidak ada laporan dengan lampiran ditemukan.",
		})
		return
	}

	// Collect PDF file paths
	// Laravel stores files in: storage/app/public/laporan/filename.pdf
	// Go backend is in: backend/ folder
	// So the path is: ../storage/app/public/ + lampiran
	var pdfPaths []string
	for _, laporan := range laporans {
		// Build the absolute path to the PDF
		storagePath := filepath.Join("..", "storage", "app", "public", laporan.Lampiran)

		// Check if file exists
		if _, err := os.Stat(storagePath); os.IsNotExist(err) {
			log.Printf("⚠️ File tidak ditemukan: %s", storagePath)
			continue
		}

		pdfPaths = append(pdfPaths, storagePath)
	}

	if len(pdfPaths) == 0 {
		c.JSON(http.StatusNotFound, gin.H{
			"status":  "error",
			"message": "File fisik laporan tidak ditemukan di server.",
		})
		return
	}

	log.Printf("📄 Mengirim %d file PDF ke Gemini API...", len(pdfPaths))

	// Call Gemini API
	summary, err := service.SummarizeWithGemini(pdfPaths)
	if err != nil {
		log.Printf("❌ Gemini API error: %v", err)
		c.JSON(http.StatusInternalServerError, gin.H{
			"status":  "error",
			"message": fmt.Sprintf("Gagal memproses AI: %v", err),
		})
		return
	}

	log.Printf("✅ Ringkasan berhasil dibuat (%d karakter)", len(summary))

	// Save summary to all matching laporan
	if err := config.DB.Model(&models.Laporan{}).
		Where("indikator_id = ? AND triwulan = ?", input.IndikatorID, input.Triwulan).
		Update("laporan_summary", summary).Error; err != nil {
		log.Printf("⚠️ Gagal menyimpan ringkasan ke database: %v", err)
	}

	c.JSON(http.StatusOK, gin.H{
		"status":  "success",
		"summary": summary,
		"count":   len(pdfPaths),
	})
}

type SummarizeYearlyRequest struct {
	Tahun string `json:"tahun" binding:"required"`
}

func SummarizeYearly(c *gin.Context) {
	var input SummarizeYearlyRequest
	if err := c.ShouldBindJSON(&input); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{
			"status":  "error",
			"message": "Input tidak valid: " + err.Error(),
		})
		return
	}

	log.Printf("📝 AI Summarize Yearly request: Tahun %s", input.Tahun)

	// Get all laporan for this year
	var laporans []models.Laporan
	if err := config.DB.Where("YEAR(tanggal) = ?", input.Tahun).Find(&laporans).Error; err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{
			"status":  "error",
			"message": "Gagal mengambil data laporan: " + err.Error(),
		})
		return
	}

	// Filter and organize those with non-empty LaporanSummary
	// Group them by IndikatorID -> Triwulan -> Summary
	groupedSummaries := make(map[int]map[string]string)
	hasAnySummary := false

	for _, lap := range laporans {
		if lap.LaporanSummary != "" {
			if _, exists := groupedSummaries[lap.IndikatorID]; !exists {
				groupedSummaries[lap.IndikatorID] = make(map[string]string)
			}
			groupedSummaries[lap.IndikatorID][lap.Triwulan] = lap.LaporanSummary
			hasAnySummary = true
		}
	}

	if !hasAnySummary {
		c.JSON(http.StatusNotFound, gin.H{
			"status":  "error",
			"message": "Tidak ada ringkasan triwulan yang ditemukan untuk tahun ini. Silakan buat ringkasan triwulan terlebih dahulu.",
		})
		return
	}

	// Call Gemini to summarize these summaries
	summaryText, err := service.SummarizeYearlyWithGemini(input.Tahun, groupedSummaries)
	if err != nil {
		c.JSON(http.StatusInternalServerError, gin.H{
			"status":  "error",
			"message": "Gagal memproses rangkuman tahunan dengan AI: " + err.Error(),
		})
		return
	}

	// Save or update to yearly_summaries table
	var yearlySummary models.YearlySummary
	err = config.DB.Where("tahun = ?", input.Tahun).First(&yearlySummary).Error
	if err != nil {
		// Create new
		yearlySummary = models.YearlySummary{
			Tahun:       input.Tahun,
			SummaryText: summaryText,
		}
		if err := config.DB.Create(&yearlySummary).Error; err != nil {
			log.Printf("⚠️ Gagal membuat rangkuman tahunan di DB: %v", err)
		}
	} else {
		// Update existing
		yearlySummary.SummaryText = summaryText
		if err := config.DB.Save(&yearlySummary).Error; err != nil {
			log.Printf("⚠️ Gagal memperbarui rangkuman tahunan di DB: %v", err)
		}
	}

	c.JSON(http.StatusOK, gin.H{
		"status":  "success",
		"summary": summaryText,
	})
}
