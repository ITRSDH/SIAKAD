# SIAKAD Frontend (siakad-ver3)

Frontend **server-rendered Blade** untuk sistem SIAKAD modern. Berbasis **Laravel 11**, menampilkan seluruh proses akademik dengan memanggil backend REST API (`api-siakad-ver3`) lewat HTTP.

> Bagian dari workspace **dev-siakad** yang berisi dua aplikasi independen: frontend Blade ini (port **8000**) dan backend API `api-siakad-ver3` (port **8001**). Kedua aplikasi dijalankan bersama namun dikembangkan terpisah.

## Ringkasan

| Aspek | Nilai |
|-------|-------|
| Framework | Laravel 11 (Blade server-rendered, **bukan SPA**) |
| Database lokal | SQLite (`database/database.sqlite`) — hanya data milik frontend sendiri |
| Port | 8000 (`php artisan serve`) |
| Timezone | `Asia/Jakarta` |
| Akses data akademik | **HTTP ke API** (Guzzle + `internal_api` key), **TIDAK akses DB API langsung** |
| Base URL API | `config/api.php` `API_BASE_URL` (default `http://localhost:8001/api/v1/`) |
| Token | Disimpan di **session** (`access_token`, `refresh_token`, `expires_at`); refresh otomatis `AutoRefreshToken` |
| Aset | Vite (`npm run dev` / `npm run build`) |

## Persyaratan

- PHP ≥ 8.2, Composer, Node.js + npm
- SQLite
- Backend `api-siakad-ver3` berjalan (port 8001) agar data akademik dapat diakses

## Setup

```bash
cp .env.example .env
composer install
npm install
php artisan migrate            # SQLite (data lokal frontend)
npm run dev                     # atau npm run build untuk produksi
php artisan serve               # port 8000
```

> **Catatan `.env.example`:** masih memuat `APP_TIMEZONE=UTC` dari boilerplate; sesuaikan ke `Asia/Jakarta` (konsisten dengan `.env` aktual).

## Konfigurasi `.env` (kunci)

| Variabel | Keterangan |
|----------|------------|
| `APP_TIMEZONE` | `Asia/Jakarta` |
| `API_BASE_URL` | Base URL API, mis. `http://localhost:8001/api/v1/` |
| `INTERNAL_API_KEY` / `INTERNAL_API_SECRET` | kredensial integrasi internal ke API (harus sama dengan API) |
| `SESSION_DRIVER` / `SESSION_LIFETIME` | session (file), umur token/refresh |
| `API_URL_KEUANGAN` | URL sistem keuangan eksternal (di luar scope, bila digunakan) |

## Alur Autentikasi

- Login → API mengembalikan token → disimpan di session.
- Middleware `require.token` / `refresh.token` / `guest.token` mengatur route.
- `AutoRefreshToken` me-refresh JWT ~5 menit sebelum expiry; gagal → logout.

## Struktur Direktori (inti)

```
app/Http/Controllers/Siakad/   ← web controllers (mirror modul API)
app/Services/DropdownService.php, DatatableResponse.php   ← helper bersama
resources/views/               ← Blade views (server-rendered)
routes/web.php                 ← route web
config/api.php                 ← base URL API
```

## Menjalankan & Menguji

- Serve: `php artisan serve --port=8000`
- Aset: `npm run dev` (dev) / `npm run build` (produksi)
- Format: `vendor/bin/pint`
- Test: `vendor/bin/phpunit`

## Catatan

- Frontend **tidak** mengakses DB API langsung; semua data akademik lewat HTTP ke API (`internal_api`).
- Token API disimpan di session; refresh otomatis via `AutoRefreshToken`.
- Timezone `Asia/Jakarta`.
- Detail arsitektur & konvensi proyek tercantum di `../AGENTS.md`.

## Lisensi

Proyek internal — kode sumber mengikuti lisensi yang berlaku di repositori workspace ini.
