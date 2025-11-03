package models

type Staff struct {
	Id          uint   `json:"id" gorm:"primaryKey"`
	Nup         string `json:"nup"`
	Nama        string `json:"nama"`
	Jabatan     string `json:"jabatan"`
	Email       string `json:"email"`
	Password    string `json:"password"`
	PangkatGol  string `json:"pangkat_gol"`
	Pendidikan  string `json:"pendidikan"`

}

