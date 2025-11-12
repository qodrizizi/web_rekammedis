@extends('layouts.app')

@section('title', 'Rekam Medis Pasien: Budi Santoso')

@section('content')

    <div class="space-y-6">
        
        <header class="bg-white p-6 rounded-2xl shadow-xl border-l-8 border-primary border-t border-gray-100">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center mb-1">
                <i class="bi bi-journal-medical text-primary mr-3"></i> Rekam Medis Digital
            </h1>
            <p class="text-xl font-semibold text-gray-900">Pasien: Budi Santoso <span class="text-primary">(P1029)</span></p>
            <p class="text-sm text-gray-600 mt-1">
                Laki-laki, 35 Tahun (15/05/1990) | NIK: 3201019876543210
            </p>
            <div class="mt-4 flex flex-wrap gap-3">
                <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-3 py-1 rounded-full flex items-center">
                    <i class="bi bi-telephone-fill mr-1"></i> 0812-3456-7890
                </span>
                <span class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full flex items-center">
                    <i class="bi bi-heart-pulse-fill mr-1"></i> Gol. Darah: O
                </span>
            </div>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

            <div class="lg:col-span-3 space-y-6">

                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-semibold text-gray-800">Riwayat Kunjungan (RM)</h2>
                    {{-- Tombol Aksi Penting --}}
                    <div class="flex space-x-3">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-xl transition-colors duration-300 flex items-center shadow-md">
                            <i class="bi bi-plus-circle-fill mr-2"></i> Buat RM Baru
                        </button>
                        <button class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-xl transition-colors duration-300 flex items-center">
                            <i class="bi bi-printer-fill mr-2"></i> Cetak Semua RM
                        </button>
                    </div>
                </div>

                {{-- Daftar Riwayat Rekam Medis --}}
                <div class="divide-y divide-gray-200 bg-white rounded-2xl shadow-xl border border-gray-100">
                    
                    <div class="p-5 hover:bg-gray-50 transition-colors border-l-4 border-yellow-500">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-900">Kunjungan: 10 November 2025</h3>
                            <span class="text-sm bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full font-medium">RM D1029/11/2025</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Dokter Pemeriksa: **Dr. Rian Setiawan, Sp.PD**</p>
                        <p class="mt-3 text-base text-gray-800">
                            **Diagnosis Utama:** Demam Tifoid
                        </p>
                        <p class="text-sm text-gray-700 mt-1">
                            **Ringkasan Catatan:** Pasien datang dengan keluhan demam tinggi 3 hari, sakit kepala, dan mual. Telah diberikan terapi antibiotik Ciprofloksasin dan antipiretik. Dianjurkan istirahat total dan kontrol 3 hari lagi.
                        </p>
                        <div class="mt-3 text-right">
                            <a href="#" class="text-sm text-primary hover:text-secondary font-medium flex items-center justify-end">
                                Lihat Detail & Resep Lengkap <i class="bi bi-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>

                    <div class="p-5 hover:bg-gray-50 transition-colors">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-900">Kunjungan: 15 Mei 2024</h3>
                            <span class="text-sm bg-gray-100 text-gray-800 px-3 py-1 rounded-full font-medium">RM D0990/05/2024</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Dokter Pemeriksa: Dr. Amelia (Poli Umum)</p>
                        <p class="mt-3 text-base text-gray-800">
                            **Diagnosis Utama:** Common Cold (Flu Biasa)
                        </p>
                        <p class="text-sm text-gray-700 mt-1">
                            **Ringkasan Catatan:** Keluhan pilek dan batuk ringan 5 hari. Diberikan obat simptomatik. Kondisi membaik setelah 5 hari.
                        </p>
                        <div class="mt-3 text-right">
                            <a href="#" class="text-sm text-primary hover:text-secondary font-medium flex items-center justify-end">
                                Lihat Detail & Resep Lengkap <i class="bi bi-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>

                </div>
            </div>

            <div class="lg:col-span-1 space-y-6">
                
                <div class="bg-white p-5 rounded-2xl shadow-xl border border-gray-100 sticky top-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="bi bi-exclamation-triangle-fill text-red-500 mr-2"></i> Peringatan Klinis
                    </h2>
                    <ul class="space-y-3">
                        <li class="text-sm text-red-700 font-medium">
                            <span class="block font-semibold">Alergi Obat:</span> Amoxicillin (reaksi ruam)
                        </li>
                        <li class="text-sm text-gray-800 font-medium border-t border-gray-100 pt-3">
                            <span class="block font-semibold">Riwayat Penyakit Kronis:</span> Tidak Ada
                        </li>
                        <li class="text-sm text-gray-800 font-medium border-t border-gray-100 pt-3">
                            <span class="block font-semibold">Jumlah Kunjungan Total:</span> 5 kali
                        </li>
                    </ul>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-xl border border-gray-100">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="bi bi-person-heart text-pink-600 mr-2"></i> Kontak Darurat
                    </h2>
                    <p class="text-base font-semibold text-gray-900">Nama: Ani (Istri)</p>
                    <p class="text-sm text-gray-600">Telp: 0811-999-000</p>
                </div>
            </div>
        </div>
    </div>

@endsection