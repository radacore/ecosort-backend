# EcoSort - Sistem Manajemen Setoran Sampah

EcoSort adalah aplikasi web dan API untuk mengelola sistem setoran sampah yang ramah lingkungan. Aplikasi ini dirancang untuk mendorong masyarakat dalam memilah dan menyetorkan sampah secara digital dengan sistem poin dan streak untuk memberikan insentif.

## Fitur Utama

- **Autentikasi Pengguna**: Sistem registrasi dan login untuk pengguna mobile
- **Setoran Sampah**: Fitur untuk mencatat dan mengelola setoran sampah
- **Sistem Poin**: Pemberian poin berdasarkan jenis dan volume sampah yang disetor
- **Sistem Streak**: Mencatat hari berturut-turut pengguna melakukan setoran
- **Panel Admin**: Antarmuka berbasis Filament untuk manajemen data
- **API Restful**: Endpoint untuk integrasi dengan aplikasi mobile
- **Manajemen Kecamatan**: Pengelompokan setoran berdasarkan wilayah kecamatan
- **Jenis Sampah**: Klasifikasi sampah dengan faktor konversi berat

## Teknologi yang Digunakan

- **Backend**: Laravel 12
- **Framework Frontend**: Blade (untuk panel admin)
- **Database**: MySQL
- **Autentikasi**: Laravel Sanctum
- **Admin Panel**: Filament PHP
- **File Upload**: Laravel Storage
- **Queue System**: Laravel Queue

## Prasyarat Sistem

- PHP 8.2 atau lebih tinggi
- Composer
- MySQL 8.0 atau lebih tinggi
- Node.js dan npm
- Ekstensi PHP: PDO, OpenSSL, Mbstring, Tokenizer, XML, Ctype, JSON

## Instalasi

### 1. Clone atau download repository

```bash
git clone <repository-url>
cd backendsampah
```

### 2. Install dependensi PHP

```bash
composer install
```

### 3. Install dependensi Node.js

```bash
npm install
```

### 4. Konfigurasi environment

Salin file `.env.example` ke `.env`:

```bash
cp .env.example .env
```

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=backendsampah1
DB_USERNAME=root
DB_PASSWORD=root
```

### 5. Generate application key

```bash
php artisan key:generate
```

### 6. Setup database

Pastikan MySQL server berjalan, lalu buat database dan jalankan migrasi:

```bash
# Buat database secara manual di MySQL
mysql -u root -p
CREATE DATABASE backendsampah1;
EXIT;

# Jalankan migrasi
php artisan migrate

# Jalankan seeding untuk data awal
php artisan db:seed
```

### 7. Setup storage

```bash
php artisan storage:link
```

## Menjalankan Aplikasi

### 1. Jalankan server development

```bash
php artisan serve
```

Aplikasi akan tersedia di `http://127.0.0.1:8000`

### 2. Jalankan queue worker (opsional, untuk background jobs)

```bash
php artisan queue:work
```

### 3. Jalankan Vite (untuk hot reload assets)

```bash
npm run dev
```

## Struktur API

### Autentikasi

- `POST /api/register` - Registrasi pengguna baru
- `POST /api/login` - Login pengguna
- `POST /api/logout` - Logout pengguna

### Setoran Sampah

- `POST /api/setoran-sampah` - Mencatat setoran sampah baru (memerlukan autentikasi)

### Profil Pengguna

- `GET /api/profil` - Mendapatkan data profil pengguna (memerlukan autentikasi)
- `PUT /api/profil` - Memperbarui profil pengguna (memerlukan autentikasi)

### Data Referensi

- `GET /api/kecamatan` - Mendapatkan daftar kecamatan
- `GET /api/berita` - Mendapatkan daftar berita

## Panel Admin

Panel admin tersedia di `/admin`. Gunakan akun admin yang dibuat melalui seeding untuk login.

## Konfigurasi Database

Aplikasi ini menggunakan MySQL sebagai database utama. Pastikan Anda telah membuat database sesuai dengan konfigurasi di file `.env`.

## Folder Struktur

```
app/                    # Core aplikasi Laravel
├── Http/              # Controllers, Middleware, Request
├── Models/            # Model Eloquent
├── Providers/         # Service providers
├── Services/          # Business logic
config/                # File konfigurasi
database/              # Migrations, Seeds, Factories
├── migrations/        # File migrasi database
├── seeders/           # File seeding data
├── factories/         # Model factories
public/                # File publik dan assets
resources/             # Views, JS, CSS
routes/                # File rute aplikasi
storage/               # File upload dan cache
```

## Testing

Jalankan test suite:

```bash
php artisan test
```

## Deployment

Untuk deployment produksi:

1. Sesuaikan konfigurasi di file `.env` untuk lingkungan produksi
2. Jalankan `php artisan config:cache` untuk caching konfigurasi
3. Jalankan `php artisan route:cache` untuk caching rute
4. Jalankan `php artisan view:cache` untuk caching view
5. Pastikan web server mengarah ke folder `public/`

## Lisensi

Proyek ini dilisensikan di bawah lisensi MIT.

## Kontribusi

Kontribusi sangat dipersilakan! Silakan fork repository ini dan kirimkan pull request untuk perbaikan atau fitur baru.

---

Dikembangkan dengan ❤️ menggunakan Laravel dan Filament PHP
