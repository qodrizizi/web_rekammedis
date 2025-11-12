@extends('layouts.app')

@section('title', 'Laporan Kinerja Klinis Saya')

@section('content')

    <div class="space-y-6">
        
        <header class="bg-white p-6 rounded-2xl shadow-xl border-l-8 border-primary border-t border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center mb-1">
                    <i class="bi bi-bar-chart-line-fill text-primary mr-3"></i> Laporan Kinerja Klinis
                </h1>
                <p class="text-lg text-gray-600">Ringkasan aktivitas Anda sebagai dokter.</p>
            </div>
            
            {{-- Filter Periode Laporan --}}
            <div class="flex items-center space-x-3">
                <label for="report-period" class="text-sm font-medium text-gray-700">Periode:</label>
                <select id="report-period" class="p-2 border border-gray-300 rounded-xl text-sm focus:ring-primary focus:border-primary transition-colors">
                    <option>Bulan Ini (November 2025)</option>
                    <option>3 Bulan Terakhir</option>
                    <option>Tahun Ini (2025)</option>
                    <option>Custom Date Range</option>
                </select>
                <button class="bg-primary hover:bg-secondary text-white font-semibold py-2 px-4 rounded-xl transition-colors duration-300 flex items-center shadow-md">
                    <i class="bi bi-download mr-2"></i> Unduh PDF
                </button>
            </div>
        </header>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 flex items-center justify-between border border-gray-100">
                <div>
                    <p class="text-sm font-medium text-gray-500">Pasien Diperiksa (Total)</p>
                    <p class="text-4xl font-bold text-gray-900 mt-1">125</p>
                </div>
                <div class="bg-primary/10 p-3 rounded-xl text-primary flex items-center justify-center">
                    <i class="bi bi-people-fill text-3xl"></i>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 flex items-center justify-between border border-gray-100">
                <div>
                    <p class="text-sm font-medium text-gray-500">RM Baru Dibuat</p>
                    <p class="text-4xl font-bold text-gray-900 mt-1">32</p>
                </div>
                <div class="bg-blue-500/10 p-3 rounded-xl text-blue-600 flex items-center justify-center">
                    <i class="bi bi-person-plus-fill text-3xl"></i>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 flex items-center justify-between border border-gray-100">
                <div>
                    <p class="text-sm font-medium text-gray-500">Diagnosis Unik Dikeluarkan</p>
                    <p class="text-4xl font-bold text-gray-900 mt-1">18</p>
                </div>
                <div class="bg-yellow-500/10 p-3 rounded-xl text-yellow-600 flex items-center justify-center">
                    <i class="bi bi-virus text-3xl"></i>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 flex items-center justify-between border border-gray-100">
                <div>
                    <p class="text-sm font-medium text-gray-500">Rata-rata Waktu Pemeriksaan</p>
                    <p class="text-4xl font-bold text-gray-900 mt-1">12 <span class="text-xl font-normal">menit</span></p>
                </div>
                <div class="bg-red-500/10 p-3 rounded-xl text-red-600 flex items-center justify-center">
                    <i class="bi bi-clock-fill text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-2">
                <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="bi bi-graph-up-arrow text-primary mr-2"></i> Tren Jumlah Pemeriksaan Bulanan
                    </h2>
                    <div class="h-80 bg-gray-100 rounded-lg flex items-center justify-center text-gray-500">
                        [Placeholder Grafik Garis/Batang: Jumlah Pasien per Bulan]
                    </div>
                </div>
            </div>
            
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="bi bi-patch-check-fill text-green-600 mr-2"></i> 5 Diagnosis Teratas
                    </h2>
                    
                    <ol class="space-y-3 list-decimal list-inside">
                        <li class="flex justify-between items-center p-2 bg-gray-50 rounded">
                            <span class="font-medium text-gray-900">1. Common Cold (Flu)</span>
                            <span class="text-primary font-bold">25 Kasus</span>
                        </li>
                        <li class="flex justify-between items-center p-2">
                            <span class="font-medium text-gray-900">2. Gastritis Akut</span>
                            <span class="text-primary font-bold">18 Kasus</span>
                        </li>
                        <li class="flex justify-between items-center p-2 bg-gray-50 rounded">
                            <span class="font-medium text-gray-900">3. Hipertensi Primer</span>
                            <span class="text-primary font-bold">15 Kasus</span>
                        </li>
                        <li class="flex justify-between items-center p-2">
                            <span class="font-medium text-gray-900">4. Diabetes Melitus Tipe 2</span>
                            <span class="text-primary font-bold">10 Kasus</span>
                        </li>
                        <li class="flex justify-between items-center p-2 bg-gray-50 rounded">
                            <span class="font-medium text-gray-900">5. Demam Tifoid</span>
                            <span class="text-primary font-bold">7 Kasus</span>
                        </li>
                    </ol>
                    <div class="mt-4 text-center">
                        <a href="#" class="text-primary hover:text-secondary text-sm font-medium transition duration-150">Lihat Semua Data Diagnosis &rarr;</a>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection