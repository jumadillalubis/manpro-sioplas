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
	// Ganti port sesuai MySQL kamu (cek di Laragon ➜ Database ➜ Info)
	port := "3306"

	// Koneksi awal tanpa database
	dsnRoot := fmt.Sprintf("root:@tcp(127.0.0.1:%s)/", port)
	db, err := gorm.Open(mysql.Open(dsnRoot), &gorm.Config{})
	if err != nil {
		log.Fatal("❌ Gagal koneksi ke MySQL:", err)
	}

	sqlDB, err := db.DB()
	if err != nil {
		log.Fatal("❌ Gagal mendapatkan instance database:", err)
	}

	// Buat database kalau belum ada
	_, err = sqlDB.Exec("CREATE DATABASE IF NOT EXISTS SIOPLAS")
	if err != nil {
		log.Fatal("❌ Gagal membuat database SIOPLAS:", err)
	}

	// Koneksi ulang ke database SIOPLAS
	dsn := fmt.Sprintf("root:@tcp(127.0.0.1:%s)/SIOPLAS?charset=utf8mb4&parseTime=True&loc=Local", port)
	DB, err = gorm.Open(mysql.Open(dsn), &gorm.Config{})
	if err != nil {
		log.Fatal("❌ Gagal koneksi ke database SIOPLAS:", err)
	}

	// Migrasi semua tabel
	err = DB.AutoMigrate(
		&models.Atasan{},
		&models.Katimja{},
		&models.Staff{},
		&models.Login{},
	)
	if err != nil {
		log.Fatal("❌ Gagal migrasi tabel:", err)
	}

	fmt.Println("✅ Database SIOPLAS siap dan semua tabel berhasil dibuat!")
}
