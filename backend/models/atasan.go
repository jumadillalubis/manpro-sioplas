package models

type Atasan struct {
	Id         uint   `json:"id" gorm:"primaryKey"`
	Nama       string `json:"nama"`
	Nip        string `json:"nip"`
	Jabatan    string `json:"jabatan"`
	Email      string `json:"email"`
	Password   string `json:"password"`
	PangkatGol string `json:"pangkat_gol"`
	Pendidikan string `json:"pendidikan"`
}
