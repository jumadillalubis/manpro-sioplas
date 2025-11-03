package models

type Katimja struct {
	Id         uint   `gorm:"primaryKey" json:"id"`
	Nama       string `json:"nama"` 
	Nip        string `json:"nip"`
	Jabatan    string `json:"jabatan"`
	Email      string `json:"email"`
	Password   string `json:"password"` 
	PangkatGol string `json:"pangkat_gol"`
	Pendidikan string `json:"pendidikan"`
	
}

// TableName mengembalikan nama tabel untuk model Katimja
func (Katimja) TableName() string {
	return "katimjas"
}
