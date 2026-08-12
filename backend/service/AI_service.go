package service

import (
	"bytes"
	"encoding/base64"
	"encoding/json"
	"fmt"
	"io"
	"net/http"
	"os"
	"time"
)

// Gemini API structures
type GeminiRequest struct {
	Contents []GeminiContent `json:"contents"`
}

type GeminiContent struct {
	Parts []GeminiPart `json:"parts"`
}

type GeminiPart struct {
	Text       string          `json:"text,omitempty"`
	InlineData *GeminiInlineData `json:"inline_data,omitempty"`
}

type GeminiInlineData struct {
	MimeType string `json:"mime_type"`
	Data     string `json:"data"`
}

type GeminiResponse struct {
	Candidates []struct {
		Content struct {
			Parts []struct {
				Text string `json:"text"`
			} `json:"parts"`
		} `json:"content"`
	} `json:"candidates"`
	Error *struct {
		Message string `json:"message"`
		Code    int    `json:"code"`
	} `json:"error"`
}

// SummarizeWithGemini sends PDF files to Google Gemini API and returns a summary
func SummarizeWithGemini(pdfPaths []string) (string, error) {
	apiKey := os.Getenv("GEMINI_API_KEY")
	if apiKey == "" {
		apiKey = loadEnvKey("GEMINI_API_KEY")
	}
	if apiKey == "" {
		return "", fmt.Errorf("GEMINI_API_KEY tidak ditemukan di environment OS maupun di file .env")
	}

	// Build the request parts
	parts := []GeminiPart{}

	// Add prompt text
	prompt := `Buatlah SATU Ringkasan Eksekutif Gabungan dari seluruh dokumen PDF yang diberikan.
Ringkasan ini harus mencakup poin-poin penting dari semua laporan yang ada, digabungkan menjadi narasi yang kohesif.

Ketentuan:
- Mulai langsung dengan judul **Ringkasan Eksekutif Gabungan**
- Gunakan subjudul jika relevan (Latar Belakang, Pelaksanaan, Hasil, Evaluasi).
- Panjang total: 400-600 kata.
- Gabungkan informasi yang tumpang tindoh dari berbagai dokumen.
- Gunakan bahasa Indonesia formal dan profesional.
- Hanya gunakan fakta dari dokumen.`

	parts = append(parts, GeminiPart{Text: prompt})

	// Add each PDF as inline data
	for _, pdfPath := range pdfPaths {
		pdfData, err := os.ReadFile(pdfPath)
		if err != nil {
			return "", fmt.Errorf("gagal membaca file PDF %s: %v", pdfPath, err)
		}

		encoded := base64.StdEncoding.EncodeToString(pdfData)
		parts = append(parts, GeminiPart{
			InlineData: &GeminiInlineData{
				MimeType: "application/pdf",
				Data:     encoded,
			},
		})
	}

	reqBody := GeminiRequest{
		Contents: []GeminiContent{
			{Parts: parts},
		},
	}

	jsonData, err := json.Marshal(reqBody)
	if err != nil {
		return "", fmt.Errorf("gagal membuat request JSON: %v", err)
	}

	// Call Gemini API
	url := fmt.Sprintf("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=%s", apiKey)

	client := &http.Client{Timeout: 120 * time.Second}
	resp, err := client.Post(url, "application/json", bytes.NewBuffer(jsonData))
	if err != nil {
		return "", fmt.Errorf("gagal menghubungi Gemini API: %v", err)
	}
	defer resp.Body.Close()

	body, err := io.ReadAll(resp.Body)
	if err != nil {
		return "", fmt.Errorf("gagal membaca response Gemini: %v", err)
	}

	var geminiResp GeminiResponse
	if err := json.Unmarshal(body, &geminiResp); err != nil {
		return "", fmt.Errorf("gagal parsing response Gemini: %v", err)
	}

	// Check for API error
	if geminiResp.Error != nil {
		return "", fmt.Errorf("Gemini API error: %s", geminiResp.Error.Message)
	}

	// Extract summary text
	if len(geminiResp.Candidates) == 0 || len(geminiResp.Candidates[0].Content.Parts) == 0 {
		return "", fmt.Errorf("Gemini API tidak mengembalikan hasil ringkasan")
	}

	summary := geminiResp.Candidates[0].Content.Parts[0].Text
	return summary, nil
}

// loadEnvKey membaca key dari file .env secara manual tanpa dependensi eksternal
func loadEnvKey(key string) string {
	paths := []string{".env", "../.env", "../../.env"}
	for _, path := range paths {
		data, err := os.ReadFile(path)
		if err != nil {
			continue
		}
		lines := bytes.Split(data, []byte("\n"))
		for _, line := range lines {
			line = bytes.TrimSpace(line)
			if len(line) == 0 || line[0] == '#' {
				continue
			}
			parts := bytes.SplitN(line, []byte("="), 2)
			if len(parts) == 2 {
				k := string(bytes.TrimSpace(parts[0]))
				v := string(bytes.TrimSpace(parts[1]))
				if len(v) >= 2 && ((v[0] == '"' && v[len(v)-1] == '"') || (v[0] == '\'' && v[len(v)-1] == '\'')) {
					v = v[1 : len(v)-1]
				}
				if k == key {
					return v
				}
			}
		}
	}
	return ""
}

// GetIndikatorName maps indicator ID to its full text name
func GetIndikatorName(id int) string {
	switch id {
	case 1:
		return "Persentase Hasil Kelautan dan Perikanan Sektor Produksi Primer yang Memenuhi Standar Mutu dan Keamanan Pangan Lingkup UPT Stasiun KIPM Pekanbaru (%)"
	case 2:
		return "Persentase Hasil Kelautan dan Perikanan Sektor Produksi Pasca Panen yang Memenuhi Standar Mutu dan Keamanan Pangan Lingkup UPT Stasiun KIPM Pekanbaru (%)"
	case 3:
		return "Rasio ekspor ikan dan hasil perikanan memenuhi syarat mutu dan diterima oleh negara tujuan ekspor lingkup UPT Stasiun KIPM Pekanbaru (%)"
	case 4:
		return "Persentase Hasil Kelautan dan Perikanan Sektor Produksi Pasca Panen yang Memenuhi Standar Mutu dan Keamanan Pangan Lingkup UPT Stasiun KIPM Pekanbaru (%)"
	case 5:
		return "Persentase Hasil Kelautan dan Perikanan Sektor Produksi Primer yang Memenuhi Standar Mutu dan Keamanan Pangan Lingkup UPT Stasiun KIPM Pekanbaru (%)"
	default:
		return fmt.Sprintf("Indikator Kinerja %d", id)
	}
}

// SummarizeYearlyWithGemini sends compiled quarterly summaries to Google Gemini API and returns a yearly summary
func SummarizeYearlyWithGemini(tahun string, groupedSummaries map[int]map[string]string) (string, error) {
	apiKey := os.Getenv("GEMINI_API_KEY")
	if apiKey == "" {
		apiKey = loadEnvKey("GEMINI_API_KEY")
	}
	if apiKey == "" {
		return "", fmt.Errorf("GEMINI_API_KEY tidak ditemukan di environment OS maupun di file .env")
	}

	// Format the summaries into text
	var buffer bytes.Buffer
	buffer.WriteString(fmt.Sprintf("Berikut adalah Ringkasan Kinerja Triwulan untuk Tahun %s:\n\n", tahun))

	for indikatorID, twMap := range groupedSummaries {
		indikatorName := GetIndikatorName(indikatorID)
		buffer.WriteString(fmt.Sprintf("### INDIKATOR %d: %s\n", indikatorID, indikatorName))
		
		periods := []string{"TW1", "TW2", "TW3", "TW4"}
		for _, tw := range periods {
			if summary, exists := twMap[tw]; exists && summary != "" {
				buffer.WriteString(fmt.Sprintf("- **%s**:\n%s\n\n", tw, summary))
			} else {
				buffer.WriteString(fmt.Sprintf("- **%s**: (Tidak ada data ringkasan)\n\n", tw))
			}
		}
		buffer.WriteString("---\n\n")
	}

	// Prepare prompt
	prompt := `Buatlah sebuah Laporan Rangkuman Kinerja Tahunan (Executive Yearly Summary) yang komprehensif, terstruktur, dan profesional berdasarkan data ringkasan triwulan yang dilampirkan di bawah ini.

Ketentuan penulisan:
1. Berikan judul utama: **Laporan Rangkuman Kinerja Tahunan - Tahun ` + tahun + `**
2. Buat pendahuluan/ringkasan eksekutif global singkat di awal.
3. Kelompokkan pembahasan berdasarkan masing-masing Indikator Kinerja yang memiliki data.
4. Tulis analisis perkembangan kinerja dari triwulan ke triwulan (misal: tren kenaikan/penurunan, pencapaian target, kendala yang dihadapi, dan evaluasi hasil kerja).
5. Buat kesimpulan dan rekomendasi aksi/strategi untuk tahun depan di bagian akhir.
6. Gunakan bahasa Indonesia formal, objektif, dan profesional.
7. Format output menggunakan Markdown yang rapi dengan heading, sub-heading, tabel (jika relevan), dan poin-poin.
8. Gunakan HANYA informasi yang disediakan dalam teks di bawah ini. Jangan mengarang fakta.

Teks Data Ringkasan Triwulan:
` + buffer.String()

	// Call Gemini API
	reqBody := GeminiRequest{
		Contents: []GeminiContent{
			{
				Parts: []GeminiPart{
					{Text: prompt},
				},
			},
		},
	}

	jsonData, err := json.Marshal(reqBody)
	if err != nil {
		return "", fmt.Errorf("gagal membuat request JSON: %v", err)
	}

	url := fmt.Sprintf("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=%s", apiKey)

	client := &http.Client{Timeout: 120 * time.Second}
	resp, err := client.Post(url, "application/json", bytes.NewBuffer(jsonData))
	if err != nil {
		return "", fmt.Errorf("gagal menghubungi Gemini API: %v", err)
	}
	defer resp.Body.Close()

	body, err := io.ReadAll(resp.Body)
	if err != nil {
		return "", fmt.Errorf("gagal membaca response Gemini: %v", err)
	}

	var geminiResp GeminiResponse
	if err := json.Unmarshal(body, &geminiResp); err != nil {
		return "", fmt.Errorf("gagal parsing response Gemini: %v", err)
	}

	// Check for API error
	if geminiResp.Error != nil {
		return "", fmt.Errorf("Gemini API error: %s", geminiResp.Error.Message)
	}

	// Extract summary text
	if len(geminiResp.Candidates) == 0 || len(geminiResp.Candidates[0].Content.Parts) == 0 {
		return "", fmt.Errorf("Gemini API tidak mengembalikan hasil ringkasan")
	}

	summary := geminiResp.Candidates[0].Content.Parts[0].Text
	return summary, nil
}
