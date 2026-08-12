package models

import "time"

type Laporan struct {
	Id              uint      `json:"id" gorm:"primaryKey"`
	Judul           string    `json:"judul"`
	Lampiran        string    `json:"lampiran"`
	Devisi          string    `json:"devisi"`
	Status          string    `json:"status"`
	Tanggal         time.Time `json:"tanggal"`
	IndikatorID     int       `json:"indikator_id"`
	Triwulan        string    `json:"triwulan"`
	Ringkasan       string    `json:"ringkasan"`
	LaporanSummary  string    `json:"laporan_summary" gorm:"column:laporan_summary"`
	Catatan         string    `json:"catatan"`
}

func (Laporan) TableName() string {
	return "laporans"
}
