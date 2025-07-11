# 📌 Sistem Absensi Karyawan Berbasis Lokasi

Sistem ini merupakan aplikasi absensi berbasis web yang dibangun menggunakan Laravel dan mendukung fitur absensi dengan validasi lokasi (radius kantor), serta dilengkapi rekap harian dan bulanan.

## 🚀 Fitur Utama

-   Login dan autentikasi pengguna
-   Absensi berbasis lokasi (GPS)
    -   Check-in dan check-out hanya bisa dilakukan di area radius kantor
-   Absensi untuk ketidak hadiran sakit & izin
-   Rekap absensi harian dan bulanan
-   Deteksi keterlambatan dan potongan penalty
-   Role Admin, HR, dan Karyawan

## 🧰 Syarat Minimum Sistem

-   OS: Windows / macOS / Linux
-   PHP: 8.2
-   Web Server: Apache / Nginx
-   Database Server: MariaDB / MySQL
-   Composer: 2.8.9
-   Laravel: 12 atau lebih

## ⚙️ Cara Menjalankan

### 1. Clone Repository

    git clone https://github.com/MRizki-S/System-ABM.git
    cd System-ABM

### 2. Install Dependency Laravel

    composer install
    cp .env.example .env
    php artisan key:generate

### 3. Install Dependecy Tailwind + Vite

    npm install
    npm run dev

### 4. Konfigurasi Database

Edit file .env:

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=NamaDatabase
    DB_USERNAME=root
    DB_PASSWORD=

### 5. Migrasi dan Seeder

    php artisan migrate --seed

### 6. Jalankan Server

    php artisan serve

Akses aplikasi di http://localhost:8000

## 👨‍💻 Developer

Rizki – Staff IT / Fullstack Developer

## 📄 Lisensi

Proyek ini bersifat internal. Untuk penggunaan di luar instansi, hubungi pengembang.
