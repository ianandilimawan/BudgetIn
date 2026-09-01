# BudgetIn — Smart Financial & Multi-Wallet Cashflow Management

<p align="center">
  <img src="public/images/logo.svg" alt="BudgetIn Logo" width="280">
</p>

<p align="center">
  <strong>Aplikasi Pencatatan Keuangan, Pengelola Multi-Dompet, Target Limit Anggaran, & Transaksi Rutin Terjadwal.</strong>
</p>

<p align="center">
  <a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 13"></a>
  <a href="https://tailwindcss.com"><img src="https://img.shields.io/badge/Tailwind_CSS-v4.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS v4"></a>
  <a href="https://livewire.laravel.com"><img src="https://img.shields.io/badge/Livewire-v4.x-FB70A9?style=for-the-badge&logo=livewire&logoColor=white" alt="Livewire"></a>
  <a href="https://php.net"><img src="https://img.shields.io/badge/PHP-8.3+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.3+"></a>
  <a href="https://github.com/ianandilimawan/BudgetIn"><img src="https://img.shields.io/badge/Tests-113%20Passed%20(391%20Assertions)-10B981?style=for-the-badge" alt="Test Suite"></a>
</p>

---

## 📖 Ringkasan / Overview

**BudgetIn** adalah platform manajemen arus kas (*cash flow*) dan *budgeting* modern yang dirancang untuk mengelola keuangan pribadi maupun bisnis mandiri secara terstruktur, rapi, dan aman.

Dibangun menggunakan arsitektur **Multi-Tenant Data Isolation**, setiap pengguna memiliki ruang data keuangan yang privat dan terlindungi secara independen tanpa risiko kebocoran data antar-pengguna.

---

## 🌟 Fitur Utama (Core Features)

### 1. 💼 Manajemen Multi-Dompet & Rekening (Cash Accounts)
- Kelola berbagai jenis rekening dan kantong kas dalam satu dashboard:
  - **Rekening Bank** (BCA, Mandiri, BRI, BNI, Jago, Seabank, dll)
  - **Uang Tunai / Cash** (Dompet fisik harian, kas kecil)
  - **E-Wallet** (GoPay, OVO, DANA, ShopeePay, LinkAja)
  - **Kartu Kredit & Pinjaman**
- **Mutasi Transfer Antar-Dompet**: Transfer saldo antar-rekening dengan perhitungan penyesuaian saldo sumber dan tujuan secara otomatis dan real-time.
- **Kategori Akun Kustom**: Buat dan sesuaikan tipe akun kas baru dengan mudah.

### 2. 📊 Dashboard Finansial Interaktif & Visual Analytics
- **Kartu Ringkasan Real-Time**: Pantau Total Pemasukan, Total Pengeluaran, Saldo Bersih (*Net Cashflow*), dan Total Nilai Aset Kas dari seluruh dompet.
- **Filter Presets Cepat**:
  - *Hari Ini, Kemarin, 7 Hari Terakhir, 30 Hari Terakhir, Bulan Ini, Bulan Lalu, Tahun Ini, hingga Rentang Tanggal Kustom (Custom Date Range)*.
- **Visualisasi ApexCharts**:
  - **Grafik Tren 6 Bulan**: Area chart interaktif membandingkan pola arus pemasukan vs pengeluaran dari bulan ke bulan.
  - **Donut Chart Komposisi Pengeluaran**: Distribusi pengeluaran berdasarkan kategori untuk mendeteksi pos pembengkakan biaya.
- **Breakdown Kategori Pengeluaran**: Menampilkan nominal, persentase kontribusi, dan jumlah transaksi per pos pengeluaran.

### 3. 🎯 Target Limit Anggaran Bulanan (Category Budget Planner)
- Atur batas pengeluaran maksimal (*budget limit*) bulanan untuk setiap kategori pengeluaran (misal: *Makanan & Minuman: Rp 2.000.000*, *Transportasi: Rp 800.000*).
- **Progress Bar & Dynamic Alert Badges**:
  - 🟢 **Aman** (< 80% limit terpakai)
  - 🟡 **Peringatan / Warning** (80% - 99% limit terpakai)
  - 🔴 **Over Budget** (>= 100% limit terlampaui)
- Menampilkan sisa anggaran yang masih bisa dibelanjakan (*Remaining Budget*).

### 4. ⏰ Transaksi Rutin Terjadwal Otomatis (Recurring Transactions)
- Otomatisasi pencatatan tagihan dan pengeluaran berkala tanpa perlu input manual berulang kali:
  - Frekuensi: **Harian (Daily), Mingguan (Weekly), Bulanan (Monthly), atau Tahunan (Yearly)**.
  - Tanggal eksekusi spesifik (misal: tanggal 1 atau tanggal gajian).
- **Scheduler Background Otomatis**: Menjalankan cron Laravel harian (`php artisan app:generate-recurring-transactions`) pada pukul `00:05` untuk memeriksa dan mencatat transaksi jatuh tempo.
- **Tombol Eksekusi Instan (Execute Now)**: Opsi pencatatan langsung di muka kapan saja.
- **Fleksibilitas Penuh**: Transaksi yang otomatis tercatat bersifat independen sehingga nominal, tanggal, maupun keterangannya tetap dapat diedit atau dihapus jika ada penyesuaian dari mutasi m-banking.

### 5. 🧾 Pencatatan Transaksi Kas & Ekspor Excel (Cash Transactions)
- Formulir transaksi cerdas dengan pemformatan mata uang otomatis (**AutoNumeric Rupiah**).
- **Upload Lampiran Bukti Transaksi (Proof of Payment)**: Unggah struk / nota pembayaran (JPG, PNG, WEBP, PDF) dengan pembatasan ukuran dan netralisasi ekstensi berbahaya.
- **Ekspor Excel 1-Klik**: Unduh rekapitulasi data transaksi dalam format file Microsoft Excel (`.xlsx`) via **OpenSpout** lengkap dengan rekapitulasi total pemasukan, pengeluaran, dan net cashflow.

### 6. 🛡️ Keamanan & Multi-Tenant Data Isolation
- **Strict Data Scoping**: Seluruh query data dibatasi secara ketat ke `user_id` yang sedang terotentikasi untuk mencegah eksploitasi IDOR (*Insecure Direct Object Reference*).
- **Otentikasi Dua Langkah (2FA OTP via Email)**: Proteksi brute-force login dengan batas percobaan 5 kali dan kadaluarsa OTP 5 menit via Laravel Cache.
- **Manajemen Pengguna Finance & Status Aktivasi**: Super Admin dapat menonaktifkan pengguna dengan middleware pemutus sesi aktif (*immediate session invalidation*).
- **Role-Based Access Control (RBAC)**: Pemisahan peran tegas antara `super-admin` dan `finance`.

### 7. 🌐 Landing Page Modern & SEO Ready
- **Cheerful & Modern SaaS Design**: Tampilan antarmuka cerah, responsif mobile, tanpa emoji, menggunakan icon vektor SVG clean.
- **Search Engine Optimization (SEO)**:
  - Meta tags komprehensif (`robots`, `googlebot`, `canonical`, `keywords`, OpenGraph, Twitter Cards).
  - **Dynamic Sitemap XML** (`/sitemap.xml`) & **Robots.txt** (`/robots.txt`).
  - **Schema.org Rich Snippets (JSON-LD)**: `WebApplication`, `FAQPage`, dan `BreadcrumbList`.

---

## 🛠️ Tech Stack

| Komponen | Teknologi | Versi |
| :--- | :--- | :--- |
| **Framework Backend** | PHP / Laravel | PHP ^8.3 / Laravel ^13.0 |
| **Frontend Styling** | Tailwind CSS (Vite plugin) | ^4.1 |
| **Reactivity & Datatables** | Livewire & PowerGrid | Livewire ^4.3 / PowerGrid ^6.10 |
| **Visual Charts** | ApexCharts | ^3.x |
| **Authorization & RBAC** | Spatie Laravel Permission | ^8.0 |
| **Spreadsheet Engine** | OpenSpout | ^4.0 |
| **Build Tool** | Vite | ^7.0 |
| **Database** | MySQL / MariaDB / PostgreSQL / SQLite | - |

---

## 🚀 Panduan Instalasi (Installation Guide)

### Prasyarat Sistem:
- **PHP** >= 8.3 dengan ekstensi: `pdo`, `mbstring`, `openssl`, `fileinfo`, `gd` / `imagick`
- **Composer** >= 2.x
- **Node.js** >= 18.x & **npm**
- **Database Server** (MySQL 8.0+ / MariaDB 10.5+)

### Langkah 1: Clone Repository
```bash
git clone git@github.com:ianandilimawan/BudgetIn.git
cd BudgetIn
```

### Langkah 2: Install Dependensi Backend & Frontend
```bash
composer install
npm install
```

### Langkah 3: Konfigurasi Environment File
Salin template `.env.example` ke `.env` dan generate application key:
```bash
cp .env.example .env
php artisan key:generate
```

Sesuaikan kredensial database pada file `.env`:
```env
APP_NAME=BudgetIn
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=budgetin_db
DB_USERNAME=root
DB_PASSWORD=
```

### Langkah 4: Jalankan Database Migration & Seeder
```bash
php artisan migrate --seed
```

> **Akun Default Bawaan Seeder:**
> - **Super Admin:** `admin@intechstudio.id` / `password`
> - **Finance User:** `finance@intechstudio.id` / `password`

### Langkah 5: Hubungkan Storage Link
```bash
php artisan storage:link
```

### Langkah 6: Jalankan Server Development
```bash
# Menjalankan server Laravel + Vite asset watcher bersamaan
composer run dev

# Atau secara terpisah:
php artisan serve
npm run dev
```

Aplikasi dapat diakses melalui browser di: `http://localhost:8000`.

---

## ⏰ Konfigurasi Otomasi Transaksi Rutin (Cron Schedule)

Untuk menjalankan pencatatan transaksi berulang secara otomatis setiap hari, tambahkan Cron Job berikut pada server Anda (cPanel, VPS, atau Ubuntu Server):

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

Atau eksekusi manual kapan saja via terminal:
```bash
php artisan app:generate-recurring-transactions
```

---

## 🧪 Pengujian Otomatis (Automated Test Suite)

BudgetIn dilengkapi dengan **89 Feature & Unit Test Cases (288 Assertions)** mencakup seluruh fungsionalitas:

```bash
# Menjalankan seluruh test suite
php artisan test

# Menjalankan pengujian spesifik isolasi tenant & keamanan
php artisan test --filter=TenantSecurityAuditTest
php artisan test --filter=CashTransactionTest
php artisan test --filter=RecurringTransactionTest
```

**Hasil Pengujian:**
```text
✓ Unit/CashSummaryServiceTest ......... 6 passed
✓ Feature/AuthTest .................... 4 passed
✓ Feature/CashAccountTest ............. 14 passed
✓ Feature/CashTransactionTest ......... 13 passed
✓ Feature/CategoryBudgetTest .......... 7 passed
✓ Feature/BudgetProjectTest ........... 5 passed
✓ Feature/RecurringTransactionTest .... 5 passed
✓ Feature/RegistrationTest ............ 4 passed
✓ Feature/TenantSecurityAuditTest ..... 10 passed
✓ Feature/SecurityAuditTest ........... 8 passed
✓ Feature/UserActivationSecurityTest .. 7 passed
...
TOTAL: 113 passed (391 assertions) - 100% SUCCESS
```

---

## 📚 Dokumentasi Sistem & Alur Aplikasi

- 📖 [**Panduan Lengkap Fitur & Alur Kerja Aplikasi (FITUR_DAN_ALUR_APLIKASI.md)**](FITUR_DAN_ALUR_APLIKASI.md): Dokumentasi mendalam tentang seluruh modul, diagram urutan (*Mermaid*), alur kerja multi-wallet, target pagu anggaran, otomasi transaksi rutin, dan konsultasi kesehatan finansial AI.
- 📘 [**Panduan Alur Sistem untuk Pentest (PENTEST_FLOW_GUIDE.md)**](PENTEST_FLOW_GUIDE.md): Peta arsitektur, boundary multi-tenancy, RBAC matrix, alur logika bisnis, dan OWASP checklist.
- 📋 [**Laporan Remediasi Hasil Pentest (PENTEST_REMEDIATION_REPORT.md)**](PENTEST_REMEDIATION_REPORT.md): Rincian lengkap perbaikan kerentanan keamanan (XSS, Type Whitelist, DoS Balance Max, Excel Formula Injection, IDOR Project Scoping) beserta panduan pengujian ulang (*retest guide*).

---

## 📁 Struktur Direktori Penting

```text
app/
├── Console/Commands/
│   └── ProcessRecurringTransactions.php  # Scheduler background transaksi rutin
├── Helpers/
│   ├── CategoryIconHelper.php            # Resolver icon kategori SVG
│   └── MenuHelper.php                    # Builder navigasi sidebar
├── Http/Controllers/
│   ├── CashAccountController.php         # CRUD Akun Kas / Dompet
│   ├── CashTransactionController.php     # Transaksi, Filter, Upload, Ekspor
│   ├── CategoryBudgetController.php      # Target Anggaran Bulanan
│   ├── FinanceUserController.php         # Manajemen Pengguna Finance
│   └── RecurringTransactionController.php# Jadwal Transaksi Berulang
├── Http/Middleware/
│   └── EnsureUserIsActive.php            # Verifikasi status akun aktif
├── Models/
│   ├── CashAccount.php                   # Model Dompet / Rekening
│   ├── CashTransaction.php               # Model Transaksi Arus Kas
│   ├── CategoryBudget.php                # Model Limit Anggaran
│   ├── RecurringTransaction.php          # Model Jadwal Berulang
│   └── TransactionCategory.php           # Model Kategori Transaksi
└── Services/
    ├── CashSummaryService.php            # Kalkulasi saldo, breakdown, dan tren
    └── FileUploadService.php             # Upload & sanitasi lampiran bukti
resources/
├── views/
│   ├── welcome.blade.php                 # Landing Page modern & SEO
│   └── admin/
│       ├── cash_accounts/                # View modul Dompet Kas
│       ├── cash_transactions/            # View modul Transaksi Kas
│       ├── recurring_transactions/       # View modul Transaksi Berulang
│       └── pages/dashboard.blade.php     # Dashboard ringkasan & grafik
```

---

## 📄 Lisensi & Pembuat

- **Dibuat oleh**: [Intech Studio](https://intechstudio.id)
- **Lisensi**: Open-source di bawah lisensi [MIT License](LICENSE).
