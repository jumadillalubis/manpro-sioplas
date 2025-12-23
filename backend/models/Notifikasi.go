package models

import "time"

type Notifikasi struct {
	Id           uint       `json:"id" gorm:"primaryKey"`
	TugasId      uint       `json:"tugas_id"`
	PenerimaId   uint       `json:"penerima_id"`   // ID dari katimja atau staff
	PenerimaTipe string     `json:"penerima_tipe"` // "katimja" atau "staff"
	Judul        string     `json:"judul"`
	Pesan        string     `json:"pesan"`
	Status       string     `json:"status"` // "unread" atau "read"
	CreatedAt    time.Time  `json:"created_at"`
	ReadAt       *time.Time `json:"read_at"`
}

func (Notifikasi) TableName() string {
	return "notifikasis"
}
