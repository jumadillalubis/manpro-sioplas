package models

import "time"

// OTP menyimpan kode one-time password untuk verifikasi login 2FA
type OTP struct {
	ID        uint      `json:"id" gorm:"primaryKey;autoIncrement"`
	Email     string    `json:"email" gorm:"index;not null"`
	Code      string    `json:"code" gorm:"not null"`        // 6-digit OTP
	ExpiredAt time.Time `json:"expired_at"`                  // Berlaku 5 menit
	Used      bool      `json:"used" gorm:"default:false"`   // Sudah dipakai atau belum
	CreatedAt time.Time `json:"created_at"`
}

func (OTP) TableName() string {
	return "otps"
}
