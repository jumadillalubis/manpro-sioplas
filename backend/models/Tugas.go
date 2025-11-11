package models

import "time"

type Tugas struct {
	Id          uint   `json:"id" gorm:"primaryKey"`
	Pembuat     string `json:"pembuat"`
	Judul       string `json:"judul"`
	Deskripsi   string `json:"deskripsi"`
	FileTugas   string `json:"file_tugas"`
	Status      string `json:"status"`
	Deadline    time.Time `json:"deadline"`
	Tanggal     time.Time `json:"tanggal"`
	Tenggat     time.Time `json:"tenggat"`
}

func (Tugas) TableName() string {
	return "tugas"
}