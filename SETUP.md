# 🚀 Project Setup Guide — Laravel Backend

Panduan ini menjelaskan langkah-langkah untuk menyiapkan lingkungan pengembangan (development environment) untuk **Smart Tourism Backend**.

## 📋 Prasyarat Sistem
- **PHP >= 8.2**
- **Composer** (Dependency Manager)
- **PostgreSQL >= 15**
- **Laragon** atau **XAMPP** (opsional, disarankan Laragon untuk Windows)
- **Node.js & NPM** (hanya untuk kompilasi asset jika diperlukan)

---

## 🛠 Langkah Instalasi

### 1. Persiapan Folder & Source Code
Clone repositori ini dan masuk ke direktori proyek:
```bash
git clone <url-repo-backend>
cd smart-tourism-backend
```

### 2. Instalasi Dependensi PHP
Jalankan composer untuk mengunduh semua library yang dibutuhkan:
```bash
composer install
```

### 3. Konfigurasi Environment
Salin file `.env.example` menjadi `.env` dan generate application key:
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Konfigurasi Database & Integrasi AI
Buka file `.env` dan sesuaikan bagian berikut:

**Database PostgreSQL:**
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=smart_tourism
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

**Integrasi FastAPI (AI Engine):**
Pastikan URL mengarah ke port tempat FastAPI berjalan (default: 8001):
```env
FASTAPI_BASE_URL=http://127.0.0.1:8001
FASTAPI_SECRET_KEY=shared_secret_key_here
```

### 5. Migrasi & Database Seeding
Jalankan migrasi untuk membuat struktur tabel dan isi data awal:
```bash
php artisan migrate --seed
```
*Catatan: Jika Anda sudah menjalankan skema SQL mentah dari repo FastAPI, Anda mungkin perlu melakukan `migrate:refresh` jika ada konflik.*

### 6. Optimasi Cache
Sangat disarankan menjalankan perintah ini setiap kali ada perubahan pada konfigurasi atau route:
```bash
php artisan optimize:clear
```

---

## 🏃 Menjalankan Server

### Server Laravel (Core API)
Jalankan server pengembangan di port 8000:
```bash
php artisan serve
```
API sekarang dapat diakses di `http://127.0.0.1:8000`.

### Admin Dashboard (Filament)
Akses dashboard admin untuk mengelola data:
`http://127.0.0.1:8000/admin`

---

## 🧪 Verifikasi Integrasi
Untuk memastikan koneksi ke AI Engine (FastAPI) berjalan dengan baik:
1. Pastikan repo `smart-tourism-api` sudah berjalan di port 8001.
2. Cek status via Laravel:
   `GET http://127.0.0.1:8000/api/v1/wisata` (Harus mengembalikan data).
3. Cek rekomendasi:
   `POST http://127.0.0.1:8000/api/v1/recommendation` (Harus bisa proxy ke FastAPI).

---

## 🛡️ Tips Troubleshooting
- **Error 401 (Unauthorized):** Pastikan header `Authorization: Bearer <token>` disertakan untuk endpoint yang diproteksi.
- **Error 502/503 (AI Service Down):** Pastikan FastAPI sudah menyala di port yang sesuai.
- **SQL Error (PgArray):** Pastikan Anda menggunakan PostgreSQL, karena proyek ini menggunakan tipe data array (`TEXT[]`) yang tidak didukung MySQL.
