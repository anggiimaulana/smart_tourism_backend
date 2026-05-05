# Smart Tourism Ciayumajakuning - Backend API

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-15-316192?style=for-the-badge&logo=postgresql)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php)

Repositori ini merupakan **Template Backend Utama** untuk proyek Smart Tourism Ciayumajakuning (Sprint 3 - Pengembangan Web). Sistem backend ini dibangun dengan Laravel 11 dan bertindak sebagai *middleware*, pengelola autentikasi, manajemen CRUD, dan *proxy* ke layer layanan Kecerdasan Buatan (FastAPI).

## 📝 Gambaran Arsitektur

Backend ini menerapkan *Layered Architecture* (Controller -> Service -> Repository -> Database) dengan aturan ketat:
1. **Frontend dilarang keras mengakses FastAPI secara langsung**.
2. **Setiap request Laravel ke FastAPI menyertakan header `X-Internal-Key`**.
3. **Database operations bebas dari `SELECT *`** — kolom diseleksi secara spesifik.

## 🚀 Fitur Utama
- **Autentikasi**: Laravel Sanctum berbasis Token (untuk SPA Next.js).
- **Admin Panel**: Filament v3 untuk manajemen seluruh resource (Wisata, Kuliner, Nongkrong, User).
- **AI Proxy**: `FastApiProxyService` menangani route `/api/v1/recommendation` dan `/api/v1/chatbot/ask` ke microservice AI.
- **FTS (Full-Text Search)**: Didukung penuh oleh PostgreSQL.

## 🛠 Panduan Instalasi (Development)

Pastikan lingkungan Anda memenuhi syarat: **PHP >= 8.2**, **PostgreSQL >= 15**, dan **Composer**.

1. **Clone repository:**
   ```bash
   git clone https://github.com/your-org/smart-tourism-backend.git
   cd smart-tourism-backend
   ```
2. **Install dependensi:**
   ```bash
   composer install
   ```
3. **Setup environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Atur koneksi database PostgreSQL di `.env`. Pastikan `FASTAPI_BASE_URL` dan `FASTAPI_SECRET_KEY` terkonfigurasi.
4. **Jalankan migrasi (jika belum menggunakan skema SQL mentah):**
   ```bash
   php artisan migrate
   ```
5. **Jalankan server pengembangan:**
   ```bash
   php artisan serve --port=8000
   ```

## 📋 Daftar Tugas (TODO) Tim

Repositori ini disiapkan sebagai *skeleton* dengan berbagai penanda `TODO`. Para developer (*Anggi* dan *Vanes*) diharapkan meninjau file [docs/BACKEND_TEMPLATE_TODO.md](docs/BACKEND_TEMPLATE_TODO.md) dan menyelesaikan implementasi pada masing-masing layer (Exception, Controller, Service, dan Repository).

## 🛡️ Standar Kualitas yang Diterapkan
- Respons API terstandar: `{ "success": boolean, "message": string, "data": array|object|null, "meta": object|null }`.
- Route Middleware: `ForceJsonResponse`, `SanitizeInput`, dan `LogApiRequest` aktif secara global di API.
- Rate Limit:
  - `auth`: 10 req/menit
  - `ai_endpoints`: 20 req/menit
  - `public`: 120 req/menit

## 📜 Lisensi
Dokumen ini bersifat internal dan diperuntukkan bagi tim pengembang proyek Smart Tourism Ciayumajakuning.
