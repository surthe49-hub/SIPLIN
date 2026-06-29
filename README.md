# SIPLIN — Sistem Inventaris Barang PLN ULP Cilacap

Aplikasi web untuk pencatatan dan pengelolaan inventaris barang di lingkungan PLN ULP Cilacap. Dibangun dengan Laravel 12 sebagai bagian dari proyek magang.

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?logo=php)
![Status](https://img.shields.io/badge/Status-Development-orange)

---

## Tentang Proyek

SIPLIN dikembangkan untuk menggantikan pencatatan inventaris manual di PLN ULP Cilacap dengan sistem terpusat yang mencakup:

- Pencatatan barang (kategori, lokasi, kondisi)
- Transfer barang antar lokasi
- Pemeliharaan dan pencatatan kerusakan
- Penghapusan barang
- Laporan inventaris dengan export PDF
- Manajemen pengguna dengan role-based access

---

## Stack

- **Backend**: Laravel 12, PHP 8.3
- **Database**: MySQL (XAMPP)
- **Frontend**: Blade + Tailwind CSS + Alpine.js
- **Build Tool**: Vite

---

## Quick Start

### 1. Clone

```bash
git clone https://github.com/surthe49-hub/SIPLIN.git
cd SIPLIN
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`, sesuaikan koneksi database:

```env
DB_DATABASE=siplin
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Database

Buat database `siplin` di phpMyAdmin atau MySQL CLI:

```sql
CREATE DATABASE siplin;
```

Lalu jalankan migrasi:

```bash
php artisan migrate --seed
```

### 5. Build Assets

```bash
npm run build
```

Untuk development dengan hot reload:

```bash
npm run dev
```

### 6. Jalankan Server

```bash
php artisan serve
```

Akses di [http://localhost:8000](http://localhost:8000).

---

## Default Login

Setelah `php artisan migrate --seed`, akun default:

| Role  | Email                  | Password   |
|-------|------------------------|------------|
| Admin | admin@inventaris.com   | admin123   |
| Staff | staff@inventaris.com   | admin123   |

> ⚠️ Ganti password sebelum digunakan di lingkungan production.

---

## Struktur Folder Utama

```
siplin/
├── app/
│   ├── Http/Controllers/     # Logic per fitur (commodity, transfer, dll)
│   ├── Models/                # Eloquent models
│   ├── Helpers/siplin.php     # Helper functions custom
│   └── Observers/             # Model observers untuk audit log
├── database/
│   ├── migrations/            # Schema database
│   └── seeders/               # Data awal (admin, kategori, lokasi)
├── resources/
│   └── views/                 # Blade templates
│       ├── auth/              # Login & register
│       ├── commodities/       # Manajemen barang
│       ├── transfers/         # Mutasi barang
│       └── reports/           # Laporan
└── routes/
    └── web.php                # Definisi route utama
```

---

## Catatan Pengembangan

Proyek ini di-fork dari template open-source SIBARAKU (`risunCode/SIBARAKU-Laravel`) lalu dimodifikasi cukup signifikan untuk kebutuhan PLN ULP Cilacap. Perubahan utama:

- Rebrand penuh ke identitas SIPLIN
- Penyederhanaan form input barang
- Hapus fitur kode referral & forced security setup (tidak relevan dengan kebutuhan internal)
- Tambah landing page publik di route `/`
- Penyesuaian alur logout

---

## Troubleshooting

**MySQL tidak terhubung** — Pastikan XAMPP MySQL aktif. Cek di Control Panel.

**419 Page Expired saat logout** — Sudah di-fix dengan menambahkan route GET `/logout`. Kalau masih muncul, jalankan `php artisan view:clear`.

**Logo tidak muncul** — File `public/images/logo-pln.png` harus ada. Cek ukuran file dan format.

**View tidak update setelah edit blade** — Jalankan:
```bash
php artisan view:clear
```
Lalu hard reload browser dengan `Ctrl+Shift+R`.

---

## Author

**Muhammad Rafi Awallaisal**  
Magang — PLN ULP Cilacap  
S1 Sistem Informasi, Telkom University Purwokerto

---

## License

Proyek internal magang. Hak cipta source code mengikuti lisensi GPL-3.0 dari template asal.