package models

import "time"

type PengumpulanLaporan struct {
	Id          uint   `json:"id" gorm:"primaryKey"`
	Judul string `json:"judul_laporan"`
	Lampiran string `json:"lampiran"`
	Devisi string `json:"devisi"`
	Status string `json:"status"`
	Tanggal time.Time `json:"tanggal"`

}

func (PengumpulanLaporan) TableName() string {
	return "pengumpulan_laporan"
}