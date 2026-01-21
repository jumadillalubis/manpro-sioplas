package models


type DetailTugas struct {
	Id          uint   `json:"id" gorm:"primaryKey"`
	KeteranganLaporan string `json:"keterangan_laporan"`
	JudulLaporan string `json:"judul_laporan"`
	Dokumen string `json:"dokumen_laporan"`
	Comment string `json:"comment"`	
}

func (DetailTugas) TableName() string {
	return "detail_tugas"
}