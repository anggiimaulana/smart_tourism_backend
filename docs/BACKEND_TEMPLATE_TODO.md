# Smart Tourism Backend - Todo List (Sprint 3)

Dokumen ini berisi daftar tugas yang perlu diselesaikan oleh tim backend (Anggi & Vanes) berdasarkan **PRD Sprint 3**. Repository ini merupakan template awal, beberapa komponen sudah disediakan skeleton-nya.

## 👩‍💻 Penugasan: Anggi (Laravel Backend)

- [x] **Task A1 — Exception & Error Handling Layer**
  - Skeleton `FastApiException` dan `AiServiceException` sudah dibuat di `app/Exceptions/`.
  - **TODO**: Implementasi logika penanganan HTTP 502 dan 503, dan pastikan response JSON konsisten.
- [x] **Task A2 — Repository Pattern Infrastructure (Wisata)**
  - Skeleton `WisataRepositoryInterface` dan `WisataRepository` sudah dibuat.
  - **TODO**: Implementasi lengkap `$listColumns` dan `$detailColumns`, logika filter, dan integrasi dengan cache.
- [ ] **Task A3 — Sentiment Controller**
  - Controller `SentimentController` sudah ada.
  - **TODO**: Implementasikan fungsi `summary()`, `predict()`, dan `sync()`. Gateway ke FastAPI wajib menggunakan `FastApiProxyService`.
- [ ] **Task A4 — Artisan Commands**
  - Skeleton `SyncSentimentCommand` dan `WarmCacheCommand` sudah tersedia.
  - **TODO**: Tambahkan argumen yang dibutuhkan dan logika per item tanpa menghentikan proses bulk (handle error secara parsial).
- [ ] **Task A5 — Filament Admin Panel (Eksklusif)**
  - Skeleton file resource Filament sudah tersedia di `app/Filament/Resources/`.
  - **TODO**: Sempurnakan form, filter tabel, role admin only, `TotalPlacesWidget`, dan `SentimentOverviewWidget`.

## 👨‍💻 Penugasan: Vanes (Laravel Backend)

- [x] **Task V1 — Repository Kuliner & Nongkrong**
  - Skeleton interface dan class repository sudah dibuat (meniru Wisata).
  - **TODO**: Definisikan kolom eksplisit, filter (khusus Nongkrong ada filter tambahan `ada_wifi`, `ada_colokan`), dan auto-generate kode unik.
- [x] **Task V2 — Service Layer Kuliner & Nongkrong**
  - Skeleton `KulinerService` dan `NongkrongService` sudah disiapkan.
  - **TODO**: Implementasi delegasi CRUD ke layer repository sesuai prinsip Dependency Inversion.
- [ ] **Task V3 — Controller Kuliner & Nongkrong**
  - Skeleton sudah ada.
  - **TODO**: Selesaikan method CRUD (index, show, store, update, destroy), pastikan memanggil service layer dan mengembalikan API Resource yang sesuai.
- [ ] **Task V4 — Search Controller**
  - Skeleton sudah disiapkan.
  - **TODO**: Implementasi PostgreSQL Full-Text Search (FTS) menggunakan view `v_all_tempat` dengan fallback `ILIKE`.
- [x] **Task V5 — Form Requests**
  - Form request untuk Kuliner, Nongkrong, Planning, Recommendation sudah digenerate.
  - **TODO**: Tulis validasi ketat dan overide method `failedValidation()` untuk mengembalikan response standard 422.
- [ ] **Task V6 — API Resources Kuliner & Nongkrong**
  - Skeleton sudah disiapkan.
  - **TODO**: Implementasikan transformasi output dan conditional fields menggunakan `$this->when()`.

---

> **Catatan:** Kode yang sudah ada di repository ini tidak boleh dihapus (kecuali refactor kecil). Fokus pada penyelesaian `TODO` yang tersebar di dalam codebase.
