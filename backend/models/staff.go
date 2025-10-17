package models

type Staff struct {
	Id          uint   `json:"id"`
	Nup         string `json:"nup"`
	Nama        string `json:"nama"`
	Jabatan     string `json:"jabatan"`
	Email       string `json:"email"`
	Password    string `json:"password"`
	Tahun_Masuk string `json:"TahunMasuk"`
	Role        string `json:"role"`
	PangkatGol  string `json:"pangkat_gol"`
	Pendidikan  string `json:"pendidikan"`
}
