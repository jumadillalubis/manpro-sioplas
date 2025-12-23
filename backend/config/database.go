package config

import (
	"fmt"
	"log"

	"SIOPLAS/models"
	"gorm.io/driver/mysql"
	"gorm.io/gorm"
)

var DB *gorm.DB

func ConnectDatabase() {
	// Koneksi awal tanpa memilih database, agar bisa buat database baru
	dsnRoot := "root:@tcp(127.0.0.1:3305)/"
	db, err := gorm.Open(mysql.Open(dsnRoot), &gorm.Config{})
	if err != nil {
		log.Fatal("❌ Gagal koneksi ke MySQL:", err)
	}

	sqlDB, err := db.DB()
	if err != nil {
		log.Fatal("❌ Gagal mendapatkan instance database:", err)
	}

	// Buat database SIOPLAS jika belum ada
	_, err = sqlDB.Exec("CREATE DATABASE IF NOT EXISTS SIOPLAS")
	if err != nil {
		log.Fatal("❌ Gagal membuat database SIOPLAS:", err)
	}

	// Koneksi ulang langsung ke database SIOPLAS
	dsn := "root:@tcp(127.0.0.1:3305)/SIOPLAS?charset=utf8mb4&parseTime=True&loc=Local"
	DB, err = gorm.Open(mysql.Open(dsn), &gorm.Config{})
	if err != nil {
		log.Fatal("❌ Gagal koneksi ke database SIOPLAS:", err)
	}

	// Migrasi semua tabel ke database
	err = DB.AutoMigrate(
		&models.Atasan{},
		&models.Katimja{},
		&models.Staff{},
		&models.Login{}, 
		&models.ResetPW{},
		&models.Tugas{},
		&models.DetailTugas{},
		&models.PengumpulanLaporan{},
		&models.Laporan{},
	)
	if err != nil {
		log.Fatal("❌ Gagal migrasi tabel:", err)
	}

	fmt.Println("✅ Database SIOPLAS siap dan semua tabel berhasil dibuat!")

}
