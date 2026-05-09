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
- **Autentikasi**: Laravel Sanctum berbasis Token.
- **Admin Panel**: Filament v3 untuk manajemen Wisata, Kuliner, Nongkrong, dan User.
- **AI Proxy**: `FastApiProxyService` menangani route `/api/v1/recommendation`, `/api/v1/recommendation/planning`, dan `/api/v1/chatbot/ask`.
- **Integrated History Tracking**: Mencatat otomatis aktivitas 'klik' user pada detail tempat ke AI Engine.
- **Global Search & FTS**: Pencarian cepat berbasis PostgreSQL Full-Text Search.
- **Sentiment Analytics**: Ringkasan performa sentimen per tempat dan per wilayah.

## 🛠 Panduan Instalasi
Lihat file [SETUP.md](SETUP.md) untuk panduan instalasi mendetail.

## 📋 Struktur Response Standar
Semua API mengembalikan format JSON yang konsisten:
- **Success:** `{ "success": true, "message": "...", "data": {...} }`
- **Not Found:** `{ "success": false, "message": "Data tidak ditemukan.", "data": null }` (Status 200 OK)
- **Error/Validasi:** `{ "success": false, "message": "...", "errors": {...} }` (Status 422/401/500)

## 🛡️ Standar Kualitas & Keamanan
- **Case-Insensitive Filters**: Filter wilayah (Indramayu, Cirebon, dll) dapat menerima input huruf kecil maupun besar.
- **Unauthorized Handling**: Response 401 yang ramah untuk akses tanpa token.
- **Rate Limit**:
  - `auth`: 10 req/menit
  - `ai_endpoints`: 20 req/menit
  - `public`: 120 req/menit

## 📜 Lisensi
Dokumen ini bersifat internal dan diperuntukkan bagi tim pengembang proyek Smart Tourism Ciayumajakuning.
