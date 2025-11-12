@extends('layouts.app')

@section('title', 'Dashboard Administrator')

@section('content')

<div class="space-y-8">
    
    <!-- Header dan Ucapan Selamat Datang -->
    <header class="bg-white p-6 rounded-2xl shadow-lg border-l-8 border-primary transition duration-300 transform hover:scale-[1.005]">
        <h1 class="text-4xl font-extrabold text-gray-800 flex items-center">
            <i class="bi bi-shield-lock text-primary mr-3"></i> Dashboard Administrator
        </h1>
        <p class="text-lg text-gray-600 mt-2">
            Selamat datang kembali di sistem rekam medis digital, **{{ Auth::user()->name }}**! 
            Anda memiliki ringkasan data penting hari ini.
        </p>
    </header>

    <!-- 1. Ringkasan Kartu Data (Stats Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        <!-- Card 1: Total Pasien -->
        <div class="bg-white p-6 rounded-2xl shadow-xl hover:shadow-2xl transition duration-300 flex items-center justify-between border border-gray-100">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Pasien Terdaftar</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">1,250</p>
            </div>
            <div class="bg-primary/10 p-3 rounded-full text-primary">
                <i class="bi bi-people-fill text-2xl"></i>
            </div>
        </div>

        <!-- Card 2: Janji Hari Ini -->
        <div class="bg-white p-6 rounded-2xl shadow-xl hover:shadow-2xl transition duration-300 flex items-center justify-between border border-gray-100">
            <div>
                <p class="text-sm font-medium text-gray-500">Janji Kunjungan Hari Ini</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">45</p>
            </div>
            <div class="bg-green-500/10 p-3 rounded-full text-green-600">
                <i class="bi bi-calendar-check text-2xl"></i>
            </div>
        </div>

        <!-- Card 3: Dokter Aktif -->
        <div class="bg-white p-6 rounded-2xl shadow-xl hover:shadow-2xl transition duration-300 flex items-center justify-between border border-gray-100">
            <div>
                <p class="text-sm font-medium text-gray-500">Dokter Aktif</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">12</p>
            </div>
            <div class="bg-yellow-500/10 p-3 rounded-full text-yellow-600">
                <i class="bi bi-person-badge-fill text-2xl"></i>
            </div>
        </div>

        <!-- Card 4: Stok Obat Kritis -->
        <div class="bg-white p-6 rounded-2xl shadow-xl hover:shadow-2xl transition duration-300 flex items-center justify-between border border-gray-100">
            <div>
                <p class="text-sm font-medium text-gray-500">Stok Obat Kritis</p>
                <p class="text-3xl font-bold text-red-600 mt-1">7</p>
            </div>
            <div class="bg-red-500/10 p-3 rounded-full text-red-600">
                <i class="bi bi-capsule-pill text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- 2. Main Content: Janji Terbaru & Log Aktivitas -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Kolom Kiri (2/3 lebar di desktop) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Grafik Penjualan atau Kunjungan (Placeholder) -->
            <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Tren Kunjungan 6 Bulan Terakhir</h2>
                <div class="h-64 bg-gray-100 rounded-lg flex items-center justify-center text-gray-500">
                    [Placeholder Grafik Batang/Garis: Data dari DB]
                </div>
            </div>

            <!-- Daftar Janji Kunjungan Terbaru -->
            <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Janji Kunjungan Mendatang</h2>
                <div class="divide-y divide-gray-200">
                    <!-- Item 1 -->
                    <div class="flex justify-between items-center py-3">
                        <div>
                            <p class="font-semibold text-gray-900">Pasien: Budi Santoso</p>
                            <p class="text-sm text-gray-500">Poli Gigi - Dr. Amelia</p>
                        </div>
                        <span class="text-xs bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-medium">10 Nov 2025, 10:00</span>
                    </div>
                    <!-- Item 2 -->
                    <div class="flex justify-between items-center py-3">
                        <div>
                            <p class="font-semibold text-gray-900">Pasien: Siti Nurhaliza</p>
                            <p class="text-sm text-gray-500">Poli Umum - Dr. Rian</p>
                        </div>
                        <span class="text-xs bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-medium">10 Nov 2025, 11:30</span>
                    </div>
                    <!-- Item 3 -->
                    <div class="flex justify-between items-center py-3">
                        <div>
                            <p class="font-semibold text-gray-900">Pasien: Joko Susilo</p>
                            <p class="text-sm text-gray-500">Poli Anak - Dr. Santi</p>
                        </div>
                        <span class="text-xs bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-medium">11 Nov 2025, 08:30</span>
                    </div>
                </div>
                <div class="mt-4 text-center">
                    <a href="#" class="text-primary hover:text-secondary text-sm font-medium">Lihat Semua Janji Kunjungan &rarr;</a>
                </div>
            </div>
        </div>
        
        <!-- Kolom Kanan (1/3 lebar di desktop) -->
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="bi bi-activity text-lg mr-2 text-gray-500"></i> Log Aktivitas Terbaru
                </h2>
                
                <ul class="space-y-4">
                    <!-- Log Item 1 -->
                    <li class="flex items-start space-x-3">
                        <div class="w-2 h-2 bg-green-500 rounded-full mt-2 flex-shrink-0"></div>
                        <div>
                            <p class="text-sm text-gray-800"><span class="font-semibold">Dr. Rian</span> telah menambah rekam medis baru untuk Pasien ID 102.</p>
                            <p class="text-xs text-gray-500">5 menit yang lalu</p>
                        </div>
                    </li>
                    <!-- Log Item 2 -->
                    <li class="flex items-start space-x-3">
                        <div class="w-2 h-2 bg-primary rounded-full mt-2 flex-shrink-0"></div>
                        <div>
                            <p class="text-sm text-gray-800"><span class="font-semibold">Petugas Ani</span> mendaftarkan pasien baru, Rina Dewi.</p>
                            <p class="text-xs text-gray-500">30 menit yang lalu</p>
                        </div>
                    </li>
                    <!-- Log Item 3 -->
                    <li class="flex items-start space-x-3">
                        <div class="w-2 h-2 bg-yellow-500 rounded-full mt-2 flex-shrink-0"></div>
                        <div>
                            <p class="text-sm text-gray-800"><span class="font-semibold">Admin</span> memperbarui stok obat Paracetamol.</p>
                            <p class="text-xs text-gray-500">1 jam yang lalu</p>
                        </div>
                    </li>
                    <!-- Log Item 4 -->
                    <li class="flex items-start space-x-3">
                        <div class="w-2 h-2 bg-red-500 rounded-full mt-2 flex-shrink-0"></div>
                        <div>
                            <p class="text-sm text-gray-800"><span class="font-semibold">Sistem</span> mendeteksi 7 obat mendekati kadaluarsa.</p>
                            <p class="text-xs text-gray-500">Kemarin, 21:00</p>
                        </div>
                    </li>
                </ul>
                <div class="mt-4 text-center">
                    <a href="#" class="text-primary hover:text-secondary text-sm font-medium">Lihat Semua Log &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection