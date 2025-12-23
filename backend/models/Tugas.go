package models

import "time"

type Tugas struct {
	Id          uint      `json:"id" gorm:"primaryKey"`
	PembuatId   uint      `json:"pembuat_id"`
	PembuatNama string    `json:"pembuat_nama"`
	Judul       string    `json:"judul"`
	Deskripsi   string    `json:"deskripsi"`
	FileTugas   string    `json:"file_tugas"`
	Status      string    `json:"status"`
	Deadline    time.Time `json:"deadline"`
	Tanggal     time.Time `json:"tanggal"`
	Tenggat     time.Time `json:"tenggat"`
	CreatedAt   time.Time `json:"created_at"`
	UpdatedAt   time.Time `json:"updated_at"`
}

func (Tugas) TableName() string {
	return "tugas"
}
