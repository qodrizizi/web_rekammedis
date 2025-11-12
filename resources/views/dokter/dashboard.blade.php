@extends('layouts.app')

{{-- Mengganti judul halaman --}}
@section('title', 'Dashboard Dokter - Rekam Medis Digital')

@section('content')

{{-- Konten Dashboard Dokter Anda dimulai di sini --}}
<div class="space-y-8 max-w-7xl mx-auto p-4 sm:p-0">
    
    {{-- Inline SVG untuk Ikon (Disimpan di sini agar mudah diakses) --}}
    <svg style="display: none;">
        <symbol id="icon-stethoscope" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10a10 10 0 1 0-20 0h2"/><path d="M10 12v6"/><path d="M14 12v6"/><path d="M12 18v4"/><path d="M12 2h2"/><path d="M12 2v2"/></symbol>
        <symbol id="icon-calendar-clock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 7.5V18a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h8.5"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/><circle cx="17" cy="15" r="3"/><path d="M17 14v1l1 1"/></symbol>
        <symbol id="icon-user-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="m19 19 2 2"/><path d="m22 17-5 5"/></symbol>
        <symbol id="icon-file-medical" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><path d="M14 2v5h5"/><path d="M12 11v6"/><path d="M9 14h6"/></symbol>
        <symbol id="icon-pills" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m20.6 15.3-.9-1.2"/><path d="m16 9-1.2-.9"/><path d="m11.2 5.3-1.4 1.4"/><path d="m5.3 11.2 1.4 1.4"/><path d="M10.4 20.6a2.13 2.13 0 0 1-2.9-2.9l12.4-12.4a2.13 2.13 0 0 1 2.9 2.9Z"/><path d="m14.8 7.6 1.4 1.4"/></symbol>
        <symbol id="icon-bell-ring" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.36 17.53a2 2 0 0 0 3.28 0"/><path d="M12 2v2"/></symbol>
    </svg>

    <header class="bg-white p-6 rounded-2xl shadow-xl border-l-8 border-primary transition duration-300 transform hover:scale-[1.005] hover:shadow-2xl">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-800 flex items-center">
            <svg class="w-8 h-8 text-primary mr-3"><use xlink:href="#icon-stethoscope"/></svg> Dashboard Dokter
        </h1>
        <p class="text-base sm:text-lg text-gray-600 mt-2">
            Selamat pagi, <strong class="text-primary">Dr. Rian Setiawan, Sp.PD</strong>! 
            Berikut ringkasan jadwal dan tugas Anda hari ini, **12 November 2025**.
        </p>
    </header>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 flex items-center justify-between border border-gray-100">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Pasien Hari Ini</p>
                <p class="text-4xl font-bold text-gray-900 mt-1">15</p>
            </div>
            <div class="bg-primary/10 p-3 rounded-xl text-primary flex items-center justify-center">
                <svg class="w-8 h-8"><use xlink:href="#icon-calendar-clock"/></svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 flex items-center justify-between border border-gray-100">
            <div>
                <p class="text-sm font-medium text-gray-500">Selesai Diperiksa</p>
                <p class="text-4xl font-bold text-gray-900 mt-1">3</p>
            </div>
            <div class="bg-blue-500/10 p-3 rounded-xl text-blue-600 flex items-center justify-center">
                <svg class="w-8 h-8"><use xlink:href="#icon-user-check"/></svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 flex items-center justify-between border border-gray-100">
            <div>
                <p class="text-sm font-medium text-gray-500">Rekam Medis Bulan Ini</p>
                <p class="text-4xl font-bold text-gray-900 mt-1">87</p>
            </div>
            <div class="bg-yellow-500/10 p-3 rounded-xl text-yellow-600 flex items-center justify-center">
                <svg class="w-8 h-8"><use xlink:href="#icon-file-medical"/></svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 flex items-center justify-between border border-gray-100">
            <div>
                <p class="text-sm font-medium text-gray-500">Resep Tertunda</p>
                <p class="text-4xl font-bold text-orange-600 mt-1">2</p>
            </div>
            <div class="bg-orange-500/10 p-3 rounded-xl text-orange-600 flex items-center justify-center">
                <svg class="w-8 h-8"><use xlink:href="#icon-pills"/></svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-6 h-6 text-primary mr-2"><use xlink:href="#icon-calendar-clock"/></svg> Jadwal Kunjungan Pasien Hari Ini
                </h2>
                <div class="divide-y divide-gray-200">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-3 border-l-4 border-yellow-500 pl-3">
                        <div>
                            <p class="font-semibold text-gray-900">Pasien: Budi Santoso (ID P1029)</p>
                            <p class="text-sm text-gray-500">Keluhan: Demam tinggi 3 hari</p>
                        </div>
                        <span class="mt-2 sm:mt-0 text-xs bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full font-medium min-w-[150px] text-center">10:00 - Sedang Diperiksa</span>
                    </div>
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-3 hover:bg-gray-50 rounded-lg -mx-2 px-2 transition">
                        <div>
                            <p class="font-semibold text-gray-900">Pasien: Siti Nurhaliza (ID P1105)</p>
                            <p class="text-sm text-gray-500">Keluhan: Sakit kepala berkepanjangan</p>
                        </div>
                        <span class="mt-2 sm:mt-0 text-xs bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-medium min-w-[150px] text-center">10:45 - Menunggu</span>
                    </div>
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-3 hover:bg-gray-50 rounded-lg -mx-2 px-2 transition">
                        <div>
                            <p class="font-semibold text-gray-900">Pasien: Joko Susilo (ID P0981)</p>
                            <p class="text-sm text-gray-500">Keluhan: Kontrol rutin pasca operasi</p>
                        </div>
                        <span class="mt-2 sm:mt-0 text-xs bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-medium min-w-[150px] text-center">13:30 - Dijadwalkan</span>
                    </div>
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-3 hover:bg-gray-50 rounded-lg -mx-2 px-2 transition">
                        <div>
                            <p class="font-semibold text-gray-900">Pasien: Rina Dewi (ID P1251)</p>
                            <p class="text-sm text-gray-500">Keluhan: Pendaftaran pertama (Poli Dalam)</p>
                        </div>
                        <span class="mt-2 sm:mt-0 text-xs bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-medium min-w-[150px] text-center">14:15 - Dijadwalkan</span>
                    </div>
                </div>
                <div class="mt-4 text-center">
                    <a href="#" class="text-primary hover:text-secondary text-sm font-medium transition duration-150">Lihat Semua Jadwal &rarr;</a>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Rekam Medis Terakhir Diperbarui Saya</h2>
                <ul class="space-y-3">
                    <li class="p-3 bg-gray-50 rounded-lg flex justify-between items-center hover:bg-gray-100 transition">
                        <div>
                            <p class="font-medium text-gray-900">Pasien: Bambang (P0822)</p>
                            <p class="text-sm text-gray-600">Diagnosis: Gastritis Akut</p>
                        </div>
                        <span class="text-xs text-gray-500">Diperbarui: 11 Nov 2025</span>
                        <a href="#" class="text-primary hover:text-secondary text-sm font-medium">Lihat Detail</a>
                    </li>
                    <li class="p-3 bg-gray-50 rounded-lg flex justify-between items-center hover:bg-gray-100 transition">
                        <div>
                            <p class="font-medium text-gray-900">Pasien: Lena Sari (P0911)</p>
                            <p class="text-sm text-gray-600">Diagnosis: Hipertensi Primer</p>
                        </div>
                        <span class="text-xs text-gray-500">Diperbarui: 10 Nov 2025</span>
                        <a href="#" class="text-primary hover:text-secondary text-sm font-medium">Lihat Detail</a>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100 sticky top-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 text-red-500 mr-2"><use xlink:href="#icon-bell-ring"/></svg> Pengingat & Notifikasi Penting
                </h2>
                
                <ul class="space-y-4">
                    <li class="flex items-start space-x-3 p-3 bg-red-50 border-l-4 border-red-500 rounded-lg">
                        <div class="w-2 h-2 bg-red-500 rounded-full mt-2.5 flex-shrink-0"></div>
                        <div>
                            <p class="text-sm text-gray-800 font-semibold">Tinjau Resep Obat</p>
                            <p class="text-sm text-gray-600">2 Resep pasien **perlu ditandatangani** segera.</p>
                            <p class="text-xs text-gray-500">Klik untuk lihat detail resep.</p>
                        </div>
                    </li>
                    <li class="flex items-start space-x-3">
                        <div class="w-2 h-2 bg-primary rounded-full mt-2.5 flex-shrink-0"></div>
                        <div>
                            <p class="text-sm text-gray-800"><span class="font-semibold">Jadwal baru:</span> Pasien Rina Dewi ditambahkan ke antrian 14:15.</p>
                            <p class="text-xs text-gray-500">5 menit yang lalu</p>
                        </div>
                    </li>
                    <li class="flex items-start space-x-3">
                        <div class="w-2 h-2 bg-blue-500 rounded-full mt-2.5 flex-shrink-0"></div>
                        <div>
                            <p class="text-sm text-gray-800"><span class="font-semibold">Pesan:</span> Perawat Siti meminta persetujuan untuk tindakan darurat.</p>
                            <p class="text-xs text-gray-500">15 menit yang lalu</p>
                        </div>
                    </li>
                </ul>
                <div class="mt-4 text-center">
                    <a href="#" class="text-primary hover:text-secondary text-sm font-medium transition duration-150">Lihat Semua Notifikasi &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection