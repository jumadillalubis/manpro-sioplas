package models

import "time"

type Login struct {
	ID          uint      `json:"id" gorm:"primaryKey"`
	Nama        string    `json:"nama"`
	Jabatan     string    `json:"jabatan"`
	Email       string    `json:"email" gorm:"unique"`
	Password    string    `json:"password"`
	Nip         string    `json:"nip"`
	Nup         string    `json:"nup"`  
	LastLogin   time.Time `json:"last_login"`
	LastLogout  time.Time `json:"last_logout"`
}

func (Login) TableName() string {
	return "logins"
}
