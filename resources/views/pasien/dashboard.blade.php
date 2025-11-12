@extends('layouts.app')

@section('title', 'Dashboard Saya')

@section('content')

<div class="space-y-8 max-w-6xl mx-auto">
    
    <!-- Header dan Ucapan Selamat Datang -->
    <header class="bg-white p-6 rounded-2xl shadow-xl border-l-8 border-indigo-500 transition duration-300 transform hover:scale-[1.005] hover:shadow-2xl">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-800 flex items-center">
            <i class="bi bi-person-circle text-indigo-600 mr-3"></i> Selamat Datang, <span class="text-indigo-600">Budi Santoso</span>
        </h1>
        <p class="text-base sm:text-lg text-gray-600 mt-2">
            ID Pasien: <strong class="text-indigo-600">P1029</strong>. Pantau riwayat kesehatan dan janji temu Anda di sini.
        </p>
    </header>

    <!-- 1. Ringkasan Status Kesehatan dan Janji Temu (Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        <!-- Card 1: Janji Temu Mendatang -->
        <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 flex items-center justify-between border border-gray-100">
            <div>
                <p class="text-sm font-medium text-gray-500">Janji Temu Mendatang</p>
                <p class="text-4xl font-bold text-gray-900 mt-1">1</p>
                <p class="text-xs text-indigo-500 font-medium mt-1">12 Nov 2025, 10:45</p>
            </div>
            <div class="bg-indigo-500/10 p-3 rounded-xl text-indigo-600 flex items-center justify-center">
                <i class="bi bi-calendar-check-fill text-3xl"></i>
            </div>
        </div>

        <!-- Card 2: Alergi Obat -->
        <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 flex items-center justify-between border border-gray-100">
            <div>
                <p class="text-sm font-medium text-gray-500">Alergi Obat Tercatat</p>
                <p class="text-4xl font-bold text-red-600 mt-1">1</p>
                <p class="text-xs text-red-500 font-medium mt-1">Amoxicillin</p>
            </div>
            <div class="bg-red-500/10 p-3 rounded-xl text-red-600 flex items-center justify-center">
                <i class="bi bi-exclamation-octagon-fill text-3xl"></i>
            </div>
        </div>

        <!-- Card 3: Resep Belum Diambil -->
        <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 flex items-center justify-between border border-gray-100">
            <div>
                <p class="text-sm font-medium text-gray-500">Resep Belum Diambil</p>
                <p class="text-4xl font-bold text-orange-600 mt-1">0</p>
                <p class="text-xs text-gray-500 font-medium mt-1">Semua resep sudah diambil</p>
            </div>
            <div class="bg-orange-500/10 p-3 rounded-xl text-orange-600 flex items-center justify-center">
                <i class="bi bi-box-seam-fill text-3xl"></i>
            </div>
        </div>

        <!-- Card 4: Kunjungan Bulan Ini -->
        <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 flex items-center justify-between border border-gray-100">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Kunjungan (2025)</p>
                <p class="text-4xl font-bold text-gray-900 mt-1">3</p>
                <p class="text-xs text-gray-500 font-medium mt-1">Terakhir: 10 Nov 2025</p>
            </div>
            <div class="bg-green-500/10 p-3 rounded-xl text-green-600 flex items-center justify-center">
                <i class="bi bi-activity text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- 2. Main Content: Aksi Cepat & Riwayat Terbaru -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Kolom Kiri (2/3 lebar): Riwayat RM & Jadwal -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Aksi Cepat -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a href="#" class="flex items-center justify-between p-5 bg-indigo-500 hover:bg-indigo-600 text-white rounded-2xl shadow-md transition duration-300">
                    <div>
                        <p class="font-bold text-xl">Buat Janji Temu</p>
                        <p class="text-sm opacity-90">Jadwalkan konsultasi berikutnya.</p>
                    </div>
                    <i class="bi bi-calendar-plus-fill text-3xl"></i>
                </a>
                <a href="#" class="flex items-center justify-between p-5 bg-yellow-500 hover:bg-yellow-600 text-white rounded-2xl shadow-md transition duration-300">
                    <div>
                        <p class="font-bold text-xl">Lihat Antrian Poli</p>
                        <p class="text-sm opacity-90">Cek status antrian kunjungan.</p>
                    </div>
                    <i class="bi bi-list-ol text-3xl"></i>
                </a>
            </div>

            <!-- Riwayat Kunjungan Terbaru -->
            <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="bi bi-journal-medical text-primary mr-2"></i> Kunjungan Rekam Medis Terbaru
                </h2>
                <div class="divide-y divide-gray-200">
                    
                    <!-- Item 1: Terbaru -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-3 hover:bg-gray-50 rounded-lg -mx-2 px-2 transition">
                        <div>
                            <p class="font-semibold text-gray-900">10 November 2025</p>
                            <p class="text-sm text-gray-500">Poli Penyakit Dalam - Dr. Rian Setiawan</p>
                        </div>
                        <span class="mt-2 sm:mt-0 text-xs bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full font-medium min-w-[120px] text-center">Diagnosis: Demam Tifoid</span>
                        <a href="#" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium mt-2 sm:mt-0">Lihat Detail RM &rarr;</a>
                    </div>
                    
                    <!-- Item 2 -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-3 hover:bg-gray-50 rounded-lg -mx-2 px-2 transition">
                        <div>
                            <p class="font-semibold text-gray-900">15 Mei 2024</p>
                            <p class="text-sm text-gray-500">Poli Umum - Dr. Amelia</p>
                        </div>
                        <span class="mt-2 sm:mt-0 text-xs bg-green-100 text-green-800 px-3 py-1 rounded-full font-medium min-w-[120px] text-center">Diagnosis: Common Cold</span>
                        <a href="#" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium mt-2 sm:mt-0">Lihat Detail RM &rarr;</a>
                    </div>
                </div>
                <div class="mt-4 text-center">
                    <a href="#" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium transition duration-150">Lihat Semua Riwayat Kunjungan &rarr;</a>
                </div>
            </div>
        </div>
        
        <!-- Kolom Kanan (1/3 lebar): Informasi Profil & Kontak -->
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100 sticky top-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="bi bi-file-person-fill text-indigo-600 mr-2"></i> Informasi Profil
                </h2>
                
                <ul class="space-y-3 text-sm text-gray-700">
                    <li class="border-b border-gray-100 pb-2">
                        <span class="font-semibold block">Tanggal Lahir:</span> 15 Mei 1990 (35 Tahun)
                    </li>
                    <li class="border-b border-gray-100 pb-2">
                        <span class="font-semibold block">Jenis Kelamin:</span> Laki-laki
                    </li>
                    <li class="border-b border-gray-100 pb-2">
                        <span class="font-semibold block">No. BPJS:</span> 0001234567890
                    </li>
                    <li class="border-b border-gray-100 pb-2">
                        <span class="font-semibold block">Kontak Darurat:</span> Ani (Istri), 0811-999-000
                    </li>
                    <li>
                        <span class="font-semibold block">Alamat:</span> Jl. Merdeka No. 12, Jakarta
                    </li>
                </ul>

                <div class="mt-4 text-center">
                    <a href="#" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium transition duration-150">Ubah Data Profil &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection