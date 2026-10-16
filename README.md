# SiagaPangan

## Sistem Informasi Monitoring Risiko dan Distribusi Pangan

SiagaPangan merupakan prototype sistem berbasis web yang dikembangkan untuk mendukung proses monitoring risiko pangan, analisis risiko, rekomendasi distribusi, pelaporan masyarakat, serta pelacakan bantuan pangan secara transparan.

Project ini dikembangkan sebagai bagian dari prototype penelitian dan bertujuan untuk menggambarkan bagaimana data terkait kondisi pangan dapat digunakan untuk membantu proses identifikasi risiko dan pengambilan keputusan terkait distribusi sumber daya pangan.

> **Catatan:** SiagaPangan saat ini merupakan prototype penelitian. Data yang ditampilkan pada sistem dapat berupa data simulasi atau data demonstrasi dan belum dapat dianggap sebagai data pangan nasional secara real-time.

---

# Fitur Utama

## 1. Dashboard

Dashboard digunakan untuk memberikan gambaran umum mengenai kondisi risiko pangan.

Informasi yang ditampilkan antara lain:

- Wilayah dengan risiko tinggi
- Komoditas kritis
- Laporan masyarakat
- Kondisi stok pangan
- Indikator risiko per daerah
- Daftar risiko daerah
- Tren harga komoditas
- Tren permintaan komoditas

Dashboard menjadi halaman utama untuk melihat kondisi pangan secara ringkas sebelum pengguna melakukan analisis lebih lanjut.

---

## 2. Analisis Risiko dan Distribusi

Halaman Analisis digunakan untuk melakukan analisis terhadap risiko pangan berdasarkan wilayah, komoditas, dan periode prediksi.

Pengguna dapat menentukan:

- Wilayah
- Komoditas
- Periode prediksi

Sistem kemudian menampilkan:

- Skor risiko
- Tingkat risiko
- Faktor penyebab risiko
- Strategi penanganan
- Rute distribusi
- Rekomendasi alokasi stok

Alur utama fitur:

```text
Pemilihan Wilayah dan Komoditas
              ↓
        Analisis Risiko
              ↓
       Faktor Penyebab
              ↓
     Strategi Penanganan
              ↓
      Rute Distribusi
              ↓
    Alokasi Sumber Daya
```

---

## 3. Laporan Masyarakat

Fitur Laporan digunakan untuk menerima laporan dari masyarakat mengenai kondisi pangan di suatu wilayah.

Laporan dapat berisi:

- Deskripsi permasalahan
- Lokasi
- Komoditas yang berkaitan
- Tingkat urgensi
- Hasil analisis laporan
- Rekomendasi awal

Fitur ini dirancang untuk membantu sistem memperoleh informasi kondisi pangan dari masyarakat sebagai salah satu sumber informasi pendukung.

---

## 4. Pelacakan Bantuan Pangan

Fitur ini digunakan untuk memantau proses distribusi bantuan pangan dari sumber menuju wilayah tujuan.

Informasi yang dapat ditampilkan meliputi:

- ID bantuan
- Sumber bantuan
- Tujuan bantuan
- Jumlah bantuan
- Waktu transaksi
- Status distribusi
- Tahapan perjalanan bantuan

Contoh alur distribusi:

```text
Gudang
   ↓
Pelabuhan / Titik Distribusi
   ↓
Wilayah Tujuan
   ↓
Desa Prioritas
   ↓
Masyarakat
```

Sistem juga menyediakan konsep QR untuk membantu proses pelacakan bantuan.

---

# Teknologi yang Digunakan

## Backend

- PHP
- Laravel 13
- Laravel Blade
- Eloquent ORM

## Frontend

- HTML
- CSS
- Bootstrap
- JavaScript
- Vite
- Chart.js

## Database

- MySQL

## Tools

- Git
- GitHub
- Composer
- Node.js
- npm
- XAMPP / Laragon

---

# Persyaratan Sistem

Sebelum menjalankan project, pastikan komputer sudah memiliki:

| Software | Versi |
|---|---|
| PHP | 8.3 atau lebih baru |
| Composer | 2.x |
| MySQL | 8.0 atau lebih baru |
| Node.js | 20.x LTS atau lebih baru |
| npm | Mengikuti instalasi Node.js |
| Git | Versi terbaru |
| Browser | Chrome / Edge / Firefox |

Selain itu, pastikan service MySQL sudah aktif.

---

# Instalasi Project

## 1. Clone Repository

Buka Terminal, Command Prompt, PowerShell, atau Git Bash.

Jalankan:

```bash
git clone https://github.com/Ogik29/siaga-pangan-frame
```

Kemudian masuk ke folder project:

```bash
cd siaga-pangan-frame
```

> Ganti `[URL_REPOSITORY]` dengan URL repository GitHub project.

---

# 2. Install Dependency Laravel

Jalankan:

```bash
composer install
```

Perintah tersebut digunakan untuk menginstall seluruh dependency PHP yang dibutuhkan oleh Laravel.

---

# 3. Install Dependency Frontend

Jalankan:

```bash
npm install
```

Perintah tersebut digunakan untuk menginstall dependency frontend yang terdapat pada `package.json`.

---

# 4. Membuat File `.env`

Laravel menggunakan file `.env` untuk menyimpan konfigurasi environment dan database.

### Windows

```powershell
copy .env.example .env
```

### macOS / Linux

```bash
cp .env.example .env
```

Setelah itu, pastikan terdapat file:

```text
.env
```

di folder utama project.

---

# 5. Konfigurasi Database

Buat database MySQL dengan nama:

```text
siaga_pangan_db
```

Database dapat dibuat menggunakan:

- phpMyAdmin
- MySQL Workbench
- MySQL Command Line
- Laragon
- XAMPP

Contoh menggunakan MySQL:

```sql
CREATE DATABASE siaga_pangan_db;
```

---

# 6. Konfigurasi File `.env`

Buka file `.env`, kemudian sesuaikan konfigurasi database.

Contoh:

```env
APP_NAME=SiagaPangan
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=siaga_pangan_db
DB_USERNAME=root
DB_PASSWORD=
```

Jika MySQL pada komputer memiliki password, masukkan password tersebut pada:

```env
DB_PASSWORD=
```

Contoh:

```env
DB_USERNAME=root
DB_PASSWORD=password_mysql
```

---

# 7. Generate Application Key

Jalankan:

```bash
php artisan key:generate
```

Perintah ini akan menghasilkan `APP_KEY` untuk aplikasi Laravel.

---

# 8. Membersihkan Cache Laravel

Setelah melakukan konfigurasi `.env`, jalankan:

```bash
php artisan optimize:clear
```

Perintah ini digunakan untuk memastikan Laravel membaca konfigurasi terbaru.

---

# 9. Menjalankan Migration

Untuk membuat tabel database sesuai dengan migration yang tersedia di project:

```bash
php artisan migrate
```

Jika project memiliki database seeder, gunakan:

```bash
php artisan migrate --seed
```

Seeder digunakan untuk memasukkan data awal atau data demonstrasi ke dalam database.

---

# 10. Storage Link

Jika project menggunakan Laravel Storage untuk file atau gambar, jalankan:

```bash
php artisan storage:link
```

Jika project tidak menggunakan Storage, perintah ini dapat dilewati.

---

# Menjalankan Project

Setelah proses instalasi selesai, project membutuhkan Laravel server dan Vite untuk menjalankan aplikasi pada lingkungan development.

## Terminal 1 — Laravel

Buka terminal pertama dan jalankan:

```bash
php artisan serve
```

Jika berhasil, Laravel akan berjalan pada:

```text
http://127.0.0.1:8000
```

---

## Terminal 2 — Vite

Buka terminal kedua pada folder project yang sama dan jalankan:

```bash
npm run dev
```

Biarkan terminal ini tetap berjalan selama proses development.

Kemudian buka browser dan akses:

```text
http://127.0.0.1:8000
```

---

# Urutan Instalasi Singkat

Jika semua software yang dibutuhkan sudah tersedia, urutan dasarnya adalah:

```bash
git clone [URL_REPOSITORY]

cd siaga-pangan-frame

composer install

npm install
```

Buat file `.env`:

```powershell
copy .env.example .env
```

Kemudian konfigurasi database:

```text
Database Name : siaga_pangan_db
Username      : root
Password      : sesuai konfigurasi MySQL
```

Selanjutnya jalankan:

```bash
php artisan key:generate

php artisan optimize:clear

php artisan migrate --seed

php artisan storage:link
```

Kemudian jalankan Laravel:

```bash
php artisan serve
```

Dan pada terminal lain:

```bash
npm run dev
```

Setelah itu buka:

```text
http://127.0.0.1:8000
```

---

# Struktur Project

Struktur utama project Laravel:

```text
siaga-pangan-frame/
│
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │
│   └── Models/
│
├── bootstrap/
│
├── config/
│
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
│
├── public/
│
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│
├── routes/
│   └── web.php
│
├── storage/
│
├── tests/
│
├── .env.example
├── artisan
├── composer.json
├── package.json
├── vite.config.js
└── README.md
```

---

# Struktur View

Halaman utama aplikasi menggunakan Laravel Blade.

Struktur view secara umum:

```text
resources/views/
│
├── layouts/
│   └── app.blade.php
│
├── components/
│   ├── navbar.blade.php
│   ├── sidebar.blade.php
│   └── ...
│
├── dashboard/
│   └── index.blade.php
│
├── analysis/
│   ├── index.blade.php
│   └── detail.blade.php
│
└── reports/
    ├── index.blade.php
    └── tracking.blade.php
```

Struktur tersebut dapat berkembang sesuai dengan kebutuhan pengembangan project.

---

# Modul Aplikasi

Aplikasi utama terdiri dari tiga menu:

```text
Dashboard
Analysis
Reports
```

## Dashboard

Digunakan untuk:

- Monitoring risiko pangan
- Melihat komoditas kritis
- Melihat kondisi stok
- Melihat laporan masyarakat
- Melihat tren harga
- Melihat tren permintaan

## Analysis

Digunakan untuk:

- Analisis risiko
- Identifikasi faktor risiko
- Strategi penanganan
- Rekomendasi distribusi
- Rekomendasi alokasi stok

## Reports

Digunakan untuk:

- Pengiriman laporan masyarakat
- Analisis laporan
- Pelacakan bantuan pangan
- Informasi transaksi distribusi

---

# Perintah Laravel yang Sering Digunakan

## Melihat daftar route

```bash
php artisan route:list
```

## Melihat status migration

```bash
php artisan migrate:status
```

## Menjalankan migration

```bash
php artisan migrate
```

## Menjalankan seeder

```bash
php artisan db:seed
```

## Migration dan seeder sekaligus

```bash
php artisan migrate --seed
```

## Membersihkan database dan menjalankan seeder kembali

```bash
php artisan migrate:fresh --seed
```

> **Perhatian:** `migrate:fresh` akan menghapus seluruh tabel dan data pada database yang digunakan. Jangan gunakan pada database yang berisi data penting.

## Membersihkan cache

```bash
php artisan optimize:clear
```

## Menjalankan server Laravel

```bash
php artisan serve
```

---

# Perintah Frontend

## Install dependency

```bash
npm install
```

## Menjalankan Vite

```bash
npm run dev
```

## Build frontend

```bash
npm run build
```

---

# Pengembangan Fitur Baru

Secara umum, proses pengembangan fitur baru mengikuti alur:

```text
Menentukan kebutuhan fitur
          ↓
Membuat / mengubah Migration
          ↓
Mengubah Model
          ↓
Mengubah / membuat Controller
          ↓
Menambahkan Route
          ↓
Membuat / mengubah Blade View
          ↓
Menambahkan CSS / JavaScript
          ↓
Menambahkan Seeder jika diperlukan
          ↓
Testing
```

---

# Contoh Membuat Model dan Migration

Jika ingin membuat model baru sekaligus migration:

```bash
php artisan make:model NamaModel -m
```

Contoh:

```bash
php artisan make:model RiskAssessment -m
```

Kemudian setelah migration selesai dibuat:

```bash
php artisan migrate
```

---

# Membuat Controller

Untuk membuat controller:

```bash
php artisan make:controller NamaController
```

Contoh:

```bash
php artisan make:controller AnalysisController
```

Untuk membuat resource controller:

```bash
php artisan make:controller NamaController --resource
```

---

# Database Development

Database project menggunakan MySQL.

Data yang digunakan pada prototype dapat mencakup beberapa kelompok informasi seperti:

```text
Wilayah
   ↓
Komoditas
   ↓
Data Risiko
   ↓
Faktor Risiko
   ↓
Rekomendasi Penanganan
   ↓
Rekomendasi Distribusi
```

Selain itu terdapat data pendukung:

```text
Riwayat Harga
Riwayat Permintaan
Laporan Masyarakat
Transaksi Bantuan
```

Hubungan antar data tersebut digunakan untuk mendukung tampilan informasi pada Dashboard, Analysis, dan Reports.

---

# Data Prototype

Data yang ditampilkan dalam aplikasi pada tahap prototype dapat berupa data simulasi atau data demonstrasi.

Contohnya:

```text
Risk Score
87
```

atau:

```text
Rice
High Risk
7 Days
```

Data tersebut digunakan untuk menunjukkan bagaimana sistem bekerja dan bagaimana hasil analisis ditampilkan kepada pengguna.

Data prototype tidak secara otomatis merepresentasikan kondisi pangan aktual atau data resmi pemerintah.

---

# Alur Sistem

Secara umum, SiagaPangan memiliki alur:

```text
Data Pangan
     ↓
Monitoring Kondisi
     ↓
Identifikasi Risiko
     ↓
Analisis Risiko
     ↓
Rekomendasi Penanganan
     ↓
Rekomendasi Distribusi
     ↓
Pelaksanaan Distribusi
     ↓
Pelacakan Bantuan
```

Laporan masyarakat juga dapat menjadi sumber informasi tambahan:

```text
Laporan Masyarakat
        ↓
Analisis Laporan
        ↓
Identifikasi Permasalahan
        ↓
Rekomendasi Tindakan
```

---

# Git Workflow

Sebelum mulai mengembangkan fitur:

```bash
git pull origin main
```

Sebaiknya setiap fitur dikembangkan pada branch tersendiri.

Contoh:

```bash
git checkout -b feature/risk-analysis
```

Setelah selesai:

```bash
git add .
```

Kemudian:

```bash
git commit -m "Add risk analysis feature"
```

Push branch:

```bash
git push origin feature/risk-analysis
```

Setelah itu branch dapat dibuat menjadi Pull Request pada GitHub.

---

# Aturan Penggunaan Git

Jangan melakukan commit terhadap:

```text
.env
/vendor/
/node_modules/
```

File `.env` berisi konfigurasi lokal dan informasi sensitif.

File dan folder dependency seperti:

```text
/vendor/
/node_modules/
```

akan dibuat kembali melalui:

```bash
composer install
```

dan:

```bash
npm install
```

File berikut tetap perlu disimpan pada repository:

```text
.env.example
composer.json
composer.lock
package.json
package-lock.json
```

---

# Troubleshooting

## 1. PHP tidak dikenali

Jika muncul:

```text
'php' is not recognized as an internal or external command
```

Pastikan PHP sudah terinstall dan sudah masuk ke PATH sistem.

Cek dengan:

```bash
php -v
```

---

## 2. Composer tidak dikenali

Cek:

```bash
composer -V
```

Jika tidak dikenali, pastikan Composer sudah terinstall dan PATH sudah dikonfigurasi.

---

## 3. Node.js atau npm tidak dikenali

Cek:

```bash
node -v
npm -v
```

Jika tidak dikenali, install Node.js kemudian buka kembali terminal.

---

## 4. Database tidak dapat terhubung

Pastikan MySQL sedang berjalan.

Kemudian cek konfigurasi `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=siaga_pangan_db
DB_USERNAME=root
DB_PASSWORD=
```

Setelah melakukan perubahan `.env`, jalankan:

```bash
php artisan optimize:clear
```

Kemudian coba:

```bash
php artisan migrate
```

---

## 5. Application key belum tersedia

Jika muncul error terkait application encryption key:

```bash
php artisan key:generate
```

---

## 6. CSS atau JavaScript tidak muncul

Pastikan Vite sedang berjalan:

```bash
npm run dev
```

Jika masih bermasalah, coba:

```bash
npm install
```

kemudian:

```bash
npm run dev
```

---

## 7. Folder `vendor` tidak ditemukan

Jalankan:

```bash
composer install
```

---

## 8. Database ingin di-reset

Untuk lingkungan development saja:

```bash
php artisan migrate:fresh --seed
```

> Perintah ini akan menghapus data yang ada pada database tersebut.

---

# Catatan untuk Developer

Setiap developer yang menjalankan project pada komputer baru harus memiliki konfigurasi `.env` masing-masing.

Jangan menggunakan atau membagikan file `.env` melalui repository.

Database lokal setiap developer menggunakan:

```text
siaga_pangan_db
```

Jika terdapat perubahan pada migration, developer lain harus menjalankan:

```bash
git pull origin main
```

kemudian:

```bash
php artisan migrate
```

Jika terdapat perubahan pada dependency PHP:

```bash
composer install
```

Jika terdapat perubahan pada dependency frontend:

```bash
npm install
```

---

# Scope Prototype

SiagaPangan pada tahap ini berfokus pada:

1. Monitoring risiko pangan
2. Analisis risiko
3. Rekomendasi distribusi
4. Pelaporan masyarakat
5. Pelacakan bantuan pangan

Prototype ini digunakan untuk menggambarkan konsep dan alur sistem yang diusulkan dalam penelitian.

Fitur seperti integrasi data real-time, machine learning secara langsung, GIS real-time, optimasi rute secara aktual, dan integrasi dengan sistem pemerintah dapat dikembangkan pada tahap selanjutnya apabila diperlukan.

---

# Disclaimer

SiagaPangan merupakan prototype untuk kebutuhan penelitian dan demonstrasi sistem.

Informasi yang ditampilkan dalam aplikasi tidak selalu merupakan data aktual dan dapat menggunakan data simulasi untuk kebutuhan demonstrasi.

Hasil risk score, prediksi, rekomendasi distribusi, dan informasi lainnya perlu dipahami berdasarkan metodologi dan batasan yang dijelaskan dalam penelitian terkait.

Sistem ini bukan merupakan sistem resmi pemerintah dan tidak dimaksudkan untuk menggantikan sistem monitoring atau distribusi pangan yang telah digunakan oleh instansi terkait.

---

# Quick Start

Jika PHP, Composer, MySQL, Node.js, npm, dan Git sudah terinstall, jalankan:

```bash
git clone [URL_REPOSITORY]

cd siaga-pangan-frame

composer install

npm install
```

Buat `.env`:

```powershell
copy .env.example .env
```

Buat database:

```text
siaga_pangan_db
```

Konfigurasi `.env`, kemudian jalankan:

```bash
php artisan key:generate

php artisan optimize:clear

php artisan migrate --seed

php artisan storage:link
```

Jalankan Laravel:

```bash
php artisan serve
```

Buka terminal kedua:

```bash
npm run dev
```

Kemudian buka browser:

```text
http://127.0.0.1:8000
```

---

# SiagaPangan

**Sistem Informasi Monitoring Risiko, Analisis, Distribusi, dan Pelacakan Bantuan Pangan**

Dikembangkan menggunakan:

- Laravel 13
- PHP
- MySQL
- Blade
- Bootstrap
- JavaScript
- Vite
