package main

import (
	"SIOPLAS/config"
	"fmt"
	"log"
)

func main() {
	config.ConnectDatabase()

	var tableName string
	var createSql string
	row := config.DB.Raw("SHOW CREATE TABLE laporans").Row()
	if err := row.Scan(&tableName, &createSql); err != nil {
		log.Fatalf("Gagal query laporans: %v", err)
	}

	fmt.Println("=== SCHEMA laporans ===")
	fmt.Println(createSql)

	fmt.Println("\n=== DATA laporans (First 10 rows) ===")
	var laporans []models.Laporan
	if err := config.DB.Limit(10).Find(&laporans).Error; err != nil {
		log.Fatalf("Gagal mengambil data laporans: %v", err)
	}

	for _, lap := range laporans {
		fmt.Printf("ID: %d, IndikatorID: %d, Triwulan: %s, Devisi: %s, Status: %s, Tanggal: %s, Lampiran: %s\n",
			lap.Id, lap.IndikatorID, lap.Triwulan, lap.Devisi, lap.Status, lap.Tanggal.Format("2006-01-02 15:04:05"), lap.Lampiran)
	}
}
