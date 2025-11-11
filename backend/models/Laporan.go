package models

import "time"

type Laporan struct {
	Id          uint   `json:"id" gorm:"primaryKey"`  
	Judul       string `json:"judul"`
	Lampiran    string `json:"lampiran"`
	Devisi      string `json:"devisi"`
	Status      string `json:"status"`
	Tanggal     time.Time `json:"tanggal"`
}

func (Laporan) TableName() string {
	return "laporans"
}