package models

import "time"

type YearlySummary struct {
	Id          uint      `json:"id" gorm:"primaryKey"`
	Tahun       string    `json:"tahun" gorm:"type:varchar(4);uniqueIndex"`
	SummaryText string    `json:"summary_text" gorm:"type:text"`
	CreatedAt   time.Time `json:"created_at"`
	UpdatedAt   time.Time `json:"updated_at"`
}

func (YearlySummary) TableName() string {
	return "yearly_summaries"
}
