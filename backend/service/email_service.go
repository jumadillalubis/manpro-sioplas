package service

import (
	"fmt"
	"net/smtp"
	"os"
)

// SMTPConfig menyimpan konfigurasi SMTP Mailtrap
type SMTPConfig struct {
	Host     string
	Port     string
	Username string
	Password string
	From     string
}

// getMailtrapConfig membaca konfigurasi dari environment variable
// atau menggunakan nilai default Mailtrap untuk development
func getMailtrapConfig() SMTPConfig {
	host := os.Getenv("MAILTRAP_HOST")
	if host == "" {
		host = "sandbox.smtp.mailtrap.io"
	}

	port := os.Getenv("MAILTRAP_PORT")
	if port == "" {
		port = "2525"
	}

	username := os.Getenv("MAILTRAP_USERNAME")
	if username == "" {
		username = "65ef734d0f5a48"
	}

	password := os.Getenv("MAILTRAP_PASSWORD")
	if password == "" {
		password = "f6c8db99205b3a"
	}

	from := os.Getenv("MAILTRAP_FROM")
	if from == "" {
		from = "noreply@sioplas.com"
	}

	return SMTPConfig{
		Host:     host,
		Port:     port,
		Username: username,
		Password: password,
		From:     from,
	}
}

// SendOTPEmail mengirimkan kode OTP ke email pengguna via Mailtrap SMTP
func SendOTPEmail(toEmail, otpCode, userName string) error {
	cfg := getMailtrapConfig()

	auth := smtp.PlainAuth("", cfg.Username, cfg.Password, cfg.Host)

	subject := "Kode OTP Login SIOPLAS"
	body := fmt.Sprintf(`<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: 'Arial', sans-serif; background-color: #f4f6f9; margin: 0; padding: 0; }
    .container { max-width: 520px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
    .header { background: linear-gradient(135deg, #6ec8e0, #4ab0cd); padding: 32px; text-align: center; }
    .header h1 { color: white; margin: 0; font-size: 22px; letter-spacing: 1px; }
    .header p { color: rgba(255,255,255,0.9); margin: 6px 0 0; font-size: 14px; }
    .content { padding: 36px 40px; }
    .greeting { color: #333; font-size: 15px; margin-bottom: 20px; }
    .otp-box { background: #f0f9fc; border: 2px dashed #6ec8e0; border-radius: 10px; text-align: center; padding: 24px; margin: 24px 0; }
    .otp-label { color: #666; font-size: 13px; margin-bottom: 10px; }
    .otp-code { font-size: 44px; font-weight: 900; letter-spacing: 12px; color: #1a7fa3; font-family: 'Courier New', monospace; }
    .expiry { color: #e05050; font-size: 13px; margin-top: 10px; font-weight: bold; }
    .info { color: #777; font-size: 13px; line-height: 1.7; }
    .footer { background: #f8f9fb; padding: 20px 40px; text-align: center; border-top: 1px solid #eee; }
    .footer p { color: #aaa; font-size: 12px; margin: 0; }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>🔐 Verifikasi Login</h1>
      <p>Sistem Informasi Pengelolaan Pelaksanaan dan Laporan Satuan</p>
    </div>
    <div class="content">
      <p class="greeting">Halo, <strong>%s</strong>!</p>
      <p class="info">Anda mencoba masuk ke sistem SIOPLAS. Gunakan kode OTP berikut untuk menyelesaikan proses login:</p>
      <div class="otp-box">
        <p class="otp-label">KODE OTP ANDA</p>
        <div class="otp-code">%s</div>
        <p class="expiry">⏱ Kode berlaku selama <strong>5 menit</strong></p>
      </div>
      <p class="info">Jangan bagikan kode ini kepada siapapun. Jika Anda tidak merasa melakukan login, abaikan email ini.</p>
    </div>
    <div class="footer">
      <p>© 2025 SIOPLAS — Sistem Informasi Dinas</p>
    </div>
  </div>
</body>
</html>`, userName, otpCode)

	message := fmt.Sprintf(
		"From: SIOPLAS <%s>\r\nTo: %s\r\nSubject: %s\r\nMIME-Version: 1.0\r\nContent-Type: text/html; charset=UTF-8\r\n\r\n%s",
		cfg.From, toEmail, subject, body,
	)

	addr := cfg.Host + ":" + cfg.Port
	err := smtp.SendMail(addr, auth, cfg.From, []string{toEmail}, []byte(message))
	if err != nil {
		return fmt.Errorf("gagal mengirim email: %w", err)
	}

	return nil
}
