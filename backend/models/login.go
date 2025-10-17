package models

type Login struct {
	ID       uint   `json:"id" gorm:"primaryKey"`
	Email    string `json:"email"`
	Password string `json:"password"`
	Role     string `json:"role"`
}
