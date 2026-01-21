package models

import "time"

type ResetPW struct {
    Id         uint      `json:"id" gorm:"primaryKey"`   
    UserId     uint      `json:"user_id"`                 
    Email      string    `json:"email"`                 
    NewPassword string   `json:"new_password"`          
    UpdatedAt  time.Time `json:"updated_at"`            
}


func (ResetPW) TableName() string {
    return "reset_passwords"
}
