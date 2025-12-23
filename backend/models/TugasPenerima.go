package models

type TugasPenerima struct {
	Id           uint   `json:"id" gorm:"primaryKey"`
	TugasId      uint   `json:"tugas_id"`
	PenerimaId   uint   `json:"penerima_id"`
	PenerimaTipe string `json:"penerima_tipe"`
	Status       string `json:"status"`
}

func (TugasPenerima) TableName() string {
	return "tugas_penerima"
}
