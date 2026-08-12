package models

import "time"

type UserNote struct {
	ID        uint      `gorm:"primaryKey" json:"id"`
	UserEmail string    `gorm:"size:255;not null;uniqueIndex" json:"user_email"`
	Content   string    `gorm:"type:text" json:"content"`
	UpdatedAt time.Time `json:"updated_at"`
}
