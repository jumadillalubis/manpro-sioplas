# Backend SIOPLAS - Golang

## 🚀 Cara Menjalankan

### 1. Install Dependencies
```bash
cd backend
go mod tidy
```

### 2. Jalankan Server
```bash
go run main.go
```

Server akan berjalan di: `http://localhost:8080`

## 📋 API Endpoints

### Login (Multi-Role)
**POST** `/api/login`

Request:
```json
{
  "username": "nama_user",
  "password": "password_user"
}
```

Response (Success):
```json
{
  "status": "success",
  "message": "Login berhasil",
  "role": "Atasan|Katimja|Staff",
  "data": {
    "id": 1,
    "nama": "Nama User",
    "email": "email@example.com",
    "jabatan": "Jabatan",
    "nip": "123456",
    "pangkat_gol": "IV/a",
    "pendidikan": "S2"
  }
}
```

Response (Error):
```json
{
  "status": "error",
  "error": "Username atau password salah"
}
```

### Endpoint Lainnya

- **GET** `/api/login` - Get all login data
- **POST** `/api/login` - Create new login data
- **GET** `/api/login/:id` - Get login by ID
- **POST** `/api/atasan/login` - Login Atasan (backward compatibility)
- **GET** `/api/atasan/:id` - Get Atasan by ID

## 🔧 Konfigurasi

Database connection diatur di `config/database.go`:
- Host: `127.0.0.1:3305`
- Database: `SIOPLAS`
- Username: `root`
- Password: `` (kosong)

## 📝 Catatan

- Backend berjalan di port `8080`
- CORS sudah dikonfigurasi untuk allow request dari `http://localhost:8000`
- Login menggunakan `nama` sebagai username (bukan email)
- Support 3 role: Atasan, Katimja, Staff
