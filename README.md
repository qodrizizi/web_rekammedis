# 🏥 Rekam Medis Digital

Sistem informasi manajemen rekam medis digital yang terintegrasi untuk memudahkan pengelolaan data kesehatan pasien, jadwal dokter, dan administrasi klinik/rumah sakit.

## 📋 Deskripsi

Rekam Medis Digital adalah aplikasi berbasis web yang dirancang untuk mendigitalisasi proses pengelolaan rekam medis dan administrasi kesehatan. Sistem ini mendukung multi-role dengan fitur yang disesuaikan untuk setiap pengguna.

## ✨ Fitur Utama

### 👨‍💼 Admin
- **Dashboard Admin** - Overview statistik dan data keseluruhan sistem
- **Data Pasien** - Manajemen data lengkap pasien
- **Data Dokter** - Pengelolaan informasi dokter dan spesialisasi
- **Data Obat** - Manajemen inventaris obat
- **Data Poli** - Pengaturan poliklinik dan layanan
- **Pendaftaran** - Registrasi pasien baru dan pendaftaran konsultasi
- **Data Rekam Medis** - Akses ke seluruh rekam medis pasien
- **Manajemen Role** - Pengaturan hak akses pengguna
- **Laporan Keseluruhan** - Generate laporan komprehensif

### 👨‍⚕️ Dokter
- **Dashboard Dokter** - Ringkasan jadwal dan pasien hari ini
- **Data Pasien** - Daftar pasien yang terdaftar ke dokter
- **Data Jadwal** - Manajemen jadwal praktek
- **Rekam Medis** - Input dan akses rekam medis pasien
- **Laporan** - Laporan kegiatan dan pasien

### 👨‍💻 Petugas
- **Dashboard Petugas** - Overview aktivitas harian
- **Pendaftaran Pasien** - Registrasi dan pendaftaran konsultasi
- **Data Pasien** - Akses informasi pasien
- **Data Obat** - Lihat ketersediaan obat
- **Resep Obat** - Kelola resep dan pemberian obat

### 👤 Pasien
- **Dashboard Pasien** - Informasi pribadi dan jadwal mendatang
- **Profil Saya** - Kelola data pribadi
- **Riwayat Medis** - Akses riwayat kesehatan dan rekam medis
- **Jadwal Konsultasi** - Lihat jadwal konsultasi dengan dokter

## 🚀 Teknologi yang Digunakan

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Tailwind](https://img.shields.io/badge/Tailwind-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

## 📦 Instalasi

### Prerequisites
```bash
# Sesuaikan dengan kebutuhan project Anda
- PHP >= 8.0
- Composer
- Node.js & npm
- MySQL/PostgreSQL
```

### Langkah Instalasi

1. Clone repository
```bash
git clone https://github.com/qodrizizi/web_rekammedis.git
cd rekam-medis-digital
```

2. Install dependencies
```bash
composer install
npm install
```

3. Setup environment
```bash
cp .env.example .env
# Edit .env sesuai konfigurasi database Anda
```

4. Generate application key
```bash
php artisan key:generate
```

5. Migrasi database
```bash
php artisan migrate --seed
```

6. Jalankan aplikasi
```bash
php artisan serve
composer run dev
```

Akses aplikasi di `http://localhost:8000`

## 👥 Default User Login

| Role | Username | Password |
|------|----------|----------|
| Admin | admin@email.com | admin123 |
| Dokter | dokter@email.com | dokter123 |
| Petugas | petugas@email.com | petugas123 |
| Pasien | pasien@email.com | pasien123 |

> ⚠️ **Penting:** Ubah password default setelah login pertama kali!

## 📸 Screenshot

<!-- Tambahkan screenshot aplikasi Anda -->
![Dashboard](screenshot/dashboard.png)
![Rekam Medis](screenshot/rekam-medis.png)

## 🗂️ Struktur Database

Sistem ini menggunakan beberapa tabel utama:
- `users` - Data pengguna sistem
- `patients` - Data pasien
- `doctors` - Data dokter
- `medical_records` - Rekam medis
- `medicines` - Data obat
- `prescriptions` - Resep obat
- `appointments` - Jadwal konsultasi
- `polyclinics` - Data poliklinik

## 🤝 Kontribusi

Kontribusi sangat diterima! Silakan fork repository ini dan buat pull request untuk fitur atau perbaikan bug.

1. Fork Project
2. Create Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to Branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## 📝 License

Distributed under the MIT License. See `LICENSE` for more information.

## 📧 Kontak
Ahmad Al Qodri Azizi Dalimunthe - ahmadalqodridalimunthe@example.com

Project Link: (https://github.com/qodrizizi/web_rekammedis.git)

## 🙏 Acknowledgments

- [Framework/Library yang digunakan]
- [Inspirasi atau referensi]
- [Contributors]

---

⭐ Jangan lupa beri star jika project ini bermanfaat!
