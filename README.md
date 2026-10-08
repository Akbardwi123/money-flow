<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="320" alt="Laravel Logo">
</p>

<h1 align="center">💸 MoneyFlow — Smart Financial & Surplus Investment Management</h1>

<p align="center">
  Aplikasi manajemen keuangan cerdas berbasis <b>Laravel</b> untuk mengontrol arus kas, memisahkan pengeluaran prioritas vs fleksibel, dan mengalokasikan <i>surplus kas</i> ke instrumen investasi finansial serta investasi keahlian (skill).
</p>

<p align="center">
  <a href="https://php.net"><img src="https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.3"></a>
  <a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-11%2B-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel"></a>
  <a href="https://tailwindcss.com"><img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS"></a>
  <a href="https://vitejs.dev"><img src="https://img.shields.io/badge/Vite-646CFF?style=for-the-badge&logo=vite&logoColor=white" alt="Vite"></a>
  <a href="https://mysql.com"><img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL"></a>
  <a href="LICENSE"><img src="https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge" alt="License"></a>
</p>

---

## 🌟 Fitur Utama (Key Features)

### 1. 📊 Real-Time Surplus & Health Indicator
- Perhitungan otomatis **Gross Surplus** dari `Total Pemasukan - Total Pengeluaran`.
- Indikator status kesehatan finansial dinamis:
  - 🟢 **Healthy** (Surplus positif)
  - 🟡 **Balanced** (Imbang)
  - 🔴 **Deficit** (Pengeluaran melebihi pemasukan)

### 2. 💵 Pencatatan Pemasukan (Incomes)
- Manajemen pencatatan berbagai sumber pemasukan (Gaji, Freelance, Bonus, Dividen, dll.) beserta filter periode bulan dan tahun.

### 3. 🎯 Pemisahan Pengeluaran Cerdas (Expenses)
- **Priority Expenses**: Pengeluaran wajib & kebutuhan primer (Sewa tempat tinggal, makanan pokok, tagihan, cicilan).
- **Flexible Expenses**: Pengeluaran sekunder & tersier (Hiburan, jajan, belanja gaya hidup) dengan rasio persentase real-time terhadap total income.

### 4. 🚀 Surplus Investment Allocation Hub
Fitur unggulan untuk mengalokasikan sisa uang lebih (surplus):
- 📈 **Financial Investment**: Reksadana, Saham, Emas, Deposito, Crypto.
- 📚 **Skill & Knowledge Investment**: Kursus, buku, sertifikasi, seminar (*investasi leher ke atas*).
- Validasi sisa kuota surplus agar tidak over-alokasi (*exceeding surplus protection*).

### 5. 📑 Laporan & Ekspor Data (Reports)
- Visualisasi grafik distribusi alokasi dan pengeluaran.
- Ekspor ringkasan laporan keuangan ke format **CSV**.
- Fitur **Demo 1-Click Login** untuk mencoba aplikasi secara instan.

---

## 📐 Formula Finansial

```text
┌────────────────────────────────────────────────────────┐
│  Gross Surplus = Total Incomes - Total Expenses        │
└───────────────────────────────────┬────────────────────┘
                                    │
       ┌────────────────────────────┴───────────────────────────┐
       ▼                                                        ▼
[ Financial Investment ]                                [ Skill Investment ]
 (Saham, Reksadana, Emas)                              (Buku, Kursus, Seminar)
       │                                                        │
       └────────────────────────────┬───────────────────────────┘
                                    │
                                    ▼
           [ Remaining Unallocated Surplus (Dana Cadangan) ]
```

---

## 🛠️ Tech Stack

- **Backend:** [Laravel](https://laravel.com/) (PHP 8.3)
- **Frontend:** [Blade Templates](https://laravel.com/docs/blade), [Tailwind CSS](https://tailwindcss.com/), [Alpine.js](https://alpinejs.dev/)
- **Build Tool:** [Vite](https://vitejs.dev/)
- **Database:** [MySQL](https://www.mysql.com/) / [SQLite](https://sqlite.org/)
- **Auth & Starter:** [Laravel Breeze](https://laravel.com/docs/starter-kits#laravel-breeze)
- **Testing:** [PHPUnit](https://phpunit.de/)

---

## 🚀 Panduan Instalasi Lokal (Getting Started)

### Prasyarat
- PHP >= 8.3
- Composer
- Node.js & NPM
- MySQL / MariaDB (atau Laragon)

### Langkah-langkah

1. **Clone repository:**
   ```bash
   git clone https://github.com/Akbardwi123/money-flow.git
   cd money-flow
   ```

2. **Install dependensi PHP & Node:**
   ```bash
   composer install
   npm install
   ```

3. **Salin konfigurasi environment:**
   ```bash
   cp .env.example .env
   ```

4. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

5. **Konfigurasi Database di `.env`:**
   Sesuaikan konfigurasi database:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=money_flow
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Jalankan Migrasi & Seeder Database:**
   ```bash
   php artisan migrate:fresh --seed
   ```

7. **Build asset frontend & Jalankan Server:**
   ```bash
   npm run build
   php artisan serve
   ```
   Aplikasi siap diakses melalui: [http://localhost:8000](http://localhost:8000)

---

## 🧪 Menjalankan Pengujian (Testing)

Proyek ini telah dilengkapi dengan suite Feature Test:
```bash
php artisan test
```

---

## 👤 Akun Uji Coba (Demo Credentials)

Gunakan tombol **Demo Login** di halaman awal atau kredensial default seeder:
- **Email:** `user@moneyflow.test`
- **Password:** `password`

---

## 📄 Lisensi

Proyek ini berada di bawah lisensi [MIT License](LICENSE).
