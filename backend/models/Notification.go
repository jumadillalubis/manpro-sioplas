package models

import "time"

type Notification struct {
	Id         uint      `json:"id" gorm:"primaryKey"`
	TugasId    string    `json:"tugas_id"`
	PenerimaId string    `json:"penerima_id"` 
	Judul      string    `json:"judul"`
	Pesan      string    `json:"pesan"`
	Status     string    `json:"status"`
	CreatedAt  time.Time `json:"created_at"`
	UpdatedAt  time.Time `json:"updated_at"`
	DeletedAt  time.Time `json:"deleted_at"`
	ReadAt     time.Time `json:"read_at"`
}

func (Notification) TableName() string {
	return "notification"
}
