package models

import "time"

type Tugas_Atasan struct {
	Id              uint      `json:"id" gorm:"primaryKey"`
	Pembuat         string    `json:"pembuat"`
	Judul           string    `json:"judul"`
	Deskripsi       string    `json:"deskripsi"`
	FileTugas       string    `json:"file_tugas"`
	FileSelesai     string    `json:"file_selesai"`
	FileSelesaiOleh string    `json:"file_selesai_oleh"`
	Status          string    `json:"status"`
	Divisi          string    `json:"divisi"`
	Penerima        string    `json:"penerima"`
	Deadline        time.Time `json:"deadline"`
	Tanggal         time.Time `json:"tanggal_buat_tugas"`
	Respon          string    `json:"respon"`
	FileRespon      string    `json:"file_respon"`
}

func (Tugas_Atasan) TableName() string {
	return "tugas_atasan"
}
