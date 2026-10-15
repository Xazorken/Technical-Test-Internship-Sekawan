# 🚗 Aplikasi Pemesanan Kendaraan

Aplikasi web untuk memonitoring dan mengelola pemesanan kendaraan perusahaan tambang nikel, mencakup konsumsi BBM, jadwal service, dan riwayat pemakaian kendaraan dengan sistem persetujuan berjenjang.

---

## 📋 Daftar Isi

- [Persyaratan Sistem](#persyaratan-sistem)
- [Tech Stack](#tech-stack)
- [Akun Default](#akun-default)
- [Instalasi & Panduan Penggunaan](#instalasi--panduan-penggunaan)
- [Fitur Aplikasi](#fitur-aplikasi)
- [Physical Data Model](#physical-data-model)
- [Activity Diagram](#activity-diagram)
- [Struktur Proyek](#struktur-proyek)

---

## ⚙️ Persyaratan Sistem

| Komponen        | Versi               |
|-----------------|---------------------|
| **PHP**         | 8.3 atau lebih baru (dikembangkan dengan PHP 8.5.2) |
| **Laravel**     | 13.x                |
| **Database**    | MySQL 8.0+ / MariaDB 10.6+ |
| **Node.js**     | 18.x atau lebih baru |
| **Composer**    | 2.x                 |
| **NPM**         | 9.x atau lebih baru |

---

## 🛠️ Tech Stack

- **Backend Framework**: Laravel 13 (PHP 8.3+)
- **Frontend**: Blade Template + Vite + Bootstrap 5
- **Database**: MySQL 8.0
- **Authentication & Authorization**: Laravel Auth + Spatie Laravel Permission
- **Activity Log**: Spatie Laravel ActivityLog
- **Export Excel**: Maatwebsite Excel 4.x
- **DataTables**: Yajra Laravel Datatables
- **Charts**: Chart.js

---

## 👤 Akun Default

Setelah menjalankan seeder, akun-akun berikut tersedia:

| Role           | Email                        | Password      | Keterangan                          |
|----------------|------------------------------|---------------|-------------------------------------|
| **Admin**      | admin@kendaraan.com          | `admin123`    | Kelola pemesanan, kendaraan, driver |
| **Approver L1**| approver1@kendaraan.com      | `approver123` | Kepala Bagian Pool (Level 1)        |
| **Approver L2**| approver2@kendaraan.com      | `approver123` | Manager Operasional (Level 2)       |

---

## 🚀 Instalasi & Panduan Penggunaan

### 1. Clone / Ekstrak Proyek

```bash
git clone <repository-url> kendaraan-app
cd kendaraan-app
```

### 2. Konfigurasi Database

Buat database MySQL baru:

```sql
CREATE DATABASE kendaraan_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 3. Salin File Environment

```bash
cp .env.example .env
```

Edit file `.env` sesuaikan konfigurasi database:

```dotenv
APP_NAME="Kendaraan App"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kendaraan_db
DB_USERNAME=root
DB_PASSWORD=          # sesuaikan dengan password MySQL Anda
```

### 4. Install Dependensi

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Jalankan Migrasi & Seeder

```bash
# Jalankan migrasi database
php artisan migrate

# Jalankan seeder (data awal + akun default)
php artisan db:seed
```

### 7. Build Assets Frontend

```bash
# Untuk development (hot reload)
npm run dev

# Atau untuk production
npm run build
```

### 8. Jalankan Aplikasi

```bash
php artisan serve
```

Aplikasi dapat diakses di: **http://localhost:8000**

> **Catatan**: Jalankan `npm run dev` dan `php artisan serve` secara bersamaan di dua terminal berbeda saat development.

---

## ✅ Fitur Aplikasi

### Role Admin
- **Dashboard** — Grafik pemakaian kendaraan (per bulan, per jenis, per lokasi)
- **Manajemen Pemesanan** — Buat, edit, batalkan pemesanan kendaraan
- **Tentukan Driver & Approver** — Pilih driver dan pihak penyetuju (L1 & L2) saat membuat pemesanan
- **Manajemen Kendaraan** — Data kendaraan milik dan sewa, status, lokasi
- **Manajemen Driver** — Data driver beserta informasi SIM
- **Manajemen Lokasi** — Kantor pusat, cabang, dan tambang
- **Laporan Periodik** — Export laporan pemesanan ke Excel dengan filter tanggal
- **Log Aktivitas** — Riwayat semua aktivitas sistem

### Role Approver (L1 & L2)
- **Dashboard Persetujuan** — Daftar pemesanan yang menunggu persetujuan
- **Approve / Reject** — Setujui atau tolak pemesanan dengan catatan
- **Persetujuan Berjenjang** — L2 hanya dapat menyetujui setelah L1 menyetujui
- **Riwayat Persetujuan** — Histori semua keputusan persetujuan

### Fitur Umum
- Login dengan email & password
- Sistem persetujuan minimal 2 level
- Log aktivitas otomatis di setiap proses
- UI/UX responsif (mobile-friendly)
- Laporan dapat di-export ke Excel

---

## 🗄️ Physical Data Model

```
users
├── id (PK)
├── name
├── email (UNIQUE)
├── password
├── phone
├── department
├── position
├── location_id (FK → locations)
├── is_active
└── timestamps

locations
├── id (PK)
├── name
├── type (pusat | cabang | tambang)
├── city
├── region
├── is_active
└── timestamps

vehicles
├── id (PK)
├── name
├── brand
├── model
├── year
├── plate_number (UNIQUE)
├── type (angkutan_orang | angkutan_barang)
├── ownership (milik | sewa)
├── rental_company (nullable)
├── status (available | in_use | maintenance)
├── location_id (FK → locations)
├── fuel_consumption (km/liter)
├── last_service_date
├── next_service_date
└── timestamps

drivers
├── id (PK)
├── name
├── license_number (UNIQUE)
├── license_type (A | B1 | B2)
├── license_expiry_date
├── phone
├── location_id (FK → locations)
├── status (available | on_duty | off)
├── is_active
└── timestamps

bookings
├── id (PK)
├── booking_number (UNIQUE)
├── user_id (FK → users)
├── vehicle_id (FK → vehicles)
├── driver_id (FK → drivers)
├── from_location_id (FK → locations)
├── destination
├── purpose
├── start_datetime
├── end_datetime
├── passenger_count
├── status (pending | approved | rejected | in_progress | completed | cancelled)
├── odometer_start (nullable)
├── odometer_end (nullable)
├── fuel_used (nullable)
├── notes
└── timestamps

booking_approvals
├── id (PK)
├── booking_id (FK → bookings)
├── approver_id (FK → users)
├── level (1 | 2)
├── status (pending | approved | rejected)
├── notes
├── acted_at (nullable)
└── timestamps

activity_log (Spatie)
├── id (PK)
├── log_name
├── description
├── subject_type / subject_id
├── causer_type / causer_id
├── properties (JSON)
└── timestamps
```

---

## 📊 Activity Diagram — Proses Pemesanan Kendaraan

```
[Admin] Buat Pemesanan
        │
        ▼
[Sistem] Simpan booking (status: pending)
         Buat BookingApproval L1 & L2
        │
        ▼
[Approver L1] Menerima notifikasi → Review pemesanan
        │
        ├── [Reject] → Status: rejected, selesai
        │
        └── [Approve] → BookingApproval L1: approved
                │
                ▼
        [Approver L2] Menerima notifikasi → Review pemesanan
                │
                ├── [Reject] → Status: rejected, selesai
                │
                └── [Approve] → BookingApproval L2: approved
                        │
                        ▼
                [Sistem] Status Booking: approved
                         Kendaraan & Driver: in_use / on_duty
                        │
                        ▼
                [Admin] Update status → in_progress (saat keberangkatan)
                        Isi odometer start
                        │
                        ▼
                [Admin] Selesaikan perjalanan → completed
                        Isi odometer end & fuel used
                        │
                        ▼
                [Sistem] Kendaraan & Driver kembali: available
                         Log aktivitas dicatat
```

---

## 📁 Struktur Proyek

```
kendaraan-app/
├── app/
│   ├── Exports/           # Export Excel (BookingExport)
│   ├── Http/
│   │   ├── Controllers/   # BookingController, VehicleController, dll
│   │   └── Middleware/
│   ├── Models/            # Booking, Vehicle, Driver, Location, User, dll
│   └── Providers/
├── database/
│   ├── migrations/        # Skema database
│   └── seeders/           # Data awal (DatabaseSeeder)
├── resources/
│   └── views/             # Blade templates
├── routes/
│   └── web.php            # Definisi routing
├── .env.example           # Template konfigurasi environment
└── README.md
```

---

## 📝 Catatan Tambahan

- Seluruh aktivitas penting (login, buat booking, approval) dicatat menggunakan **Spatie ActivityLog**
- Laporan Excel dapat difilter berdasarkan rentang tanggal dan status
- Persetujuan berjenjang: L2 **tidak bisa** approve sebelum L1 approve
- Kendaraan sewa memiliki field tambahan `rental_company`

---

*Dibuat untuk Technical Test Fullstack Developer Intern — PT Sekawan Media Informatika*
