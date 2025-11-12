@extends('layouts.app')

@section('title', 'Riwayat Rekam Medis Saya')

@section('content')

    <div class="space-y-6 max-w-5xl mx-auto">
        
        <!-- Header Halaman dan Ringkasan Pasien -->
        <header class="bg-white p-6 rounded-2xl shadow-xl border-l-8 border-indigo-500 border-t border-gray-100">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center mb-1">
                <i class="bi bi-journal-medical text-indigo-600 mr-3"></i> Riwayat Kesehatan Saya
            </h1>
            <p class="text-lg text-gray-600">Ringkasan kunjungan dan catatan medis Anda.</p>
        </header>

        <!-- Area Filter dan Pencarian -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0 p-4 bg-white rounded-2xl shadow border border-gray-100">
            
            <!-- Search Bar -->
            <div class="w-full md:w-1/2">
                <div class="relative">
                    <input type="text" placeholder="Cari berdasarkan Tanggal, Dokter, atau Diagnosis..." 
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                        aria-label="Cari Riwayat">
                    <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>

            <!-- Filter Tahun -->
            <div class="flex items-center space-x-3">
                <label for="filter-year" class="text-sm font-medium text-gray-700">Tahun Kunjungan:</label>
                <select id="filter-year" class="p-2 border border-gray-300 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    <option>Semua Tahun</option>
                    <option>2025</option>
                    <option>2024</option>
                </select>
            </div>
        </div>

        <!-- Daftar Riwayat Rekam Medis -->
        <div class="space-y-4">
            
            {{-- Data Dummy Riwayat Kunjungan --}}
            @php
                $visitHistory = [
                    [
                        'date' => '10 November 2025', 
                        'doctor' => 'Dr. Rian Setiawan, Sp.PD', 
                        'poli' => 'Penyakit Dalam', 
                        'diagnosis' => 'Demam Tifoid', 
                        'summary' => 'Kunjungan untuk keluhan demam tinggi dan mual. Telah diberikan obat dan anjuran istirahat.'
                    ],
                    [
                        'date' => '05 April 2025', 
                        'doctor' => 'Dr. Santi (Poli Anak)', 
                        'poli' => 'Poli Anak', 
                        'diagnosis' => 'Vaksinasi Rutin', 
                        'summary' => 'Pemberian imunisasi rutin sesuai jadwal untuk anak.'
                    ],
                    [
                        'date' => '15 Mei 2024', 
                        'doctor' => 'Dr. Amelia', 
                        'poli' => 'Poli Umum', 
                        'diagnosis' => 'Common Cold (Flu)', 
                        'summary' => 'Keluhan pilek dan batuk ringan. Diberikan obat pereda gejala.'
                    ],
                ];
            @endphp

            @foreach($visitHistory as $visit)
            <div class="bg-white p-5 rounded-2xl shadow hover:shadow-lg transition-all duration-300 border-l-4 border-indigo-500">
                
                {{-- Header Kunjungan --}}
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-gray-100 pb-3 mb-3">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <i class="bi bi-calendar-check-fill text-lg text-indigo-500 mr-2"></i> {{ $visit['date'] }}
                    </h3>
                    <span class="text-sm bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full font-medium mt-2 sm:mt-0">
                        {{ $visit['poli'] }}
                    </span>
                </div>

                {{-- Detail Ringkas --}}
                <div class="space-y-2">
                    <p class="text-sm text-gray-700">
                        <span class="font-semibold text-gray-800 block">Dokter:</span> {{ $visit['doctor'] }}
                    </p>
                    <p class="text-sm text-gray-700">
                        <span class="font-semibold text-gray-800 block">Diagnosis Utama:</span> 
                        <span class="bg-yellow-100 text-yellow-800 font-medium px-2 py-0.5 rounded">{{ $visit['diagnosis'] }}</span>
                    </p>
                    <p class="text-sm text-gray-700 pt-1">
                        <span class="font-semibold text-gray-800 block">Catatan Ringkas:</span> {{ $visit['summary'] }}
                    </p>
                </div>

                {{-- Tombol Aksi --}}
                <div class="mt-4 flex justify-end space-x-3 border-t border-gray-100 pt-3">
                    <a href="#" class="text-sm text-red-600 hover:text-red-800 font-medium flex items-center">
                        <i class="bi bi-file-earmark-medical-fill mr-1"></i> Lihat Resep & Tindakan
                    </a>
                </div>
            </div>
            @endforeach

            {{-- Placeholder jika tidak ada data --}}
            @if(empty($visitHistory))
            <div class="text-center p-10 bg-white rounded-2xl shadow border border-gray-100">
                <i class="bi bi-x-octagon-fill text-gray-400 text-4xl mb-3"></i>
                <p class="text-gray-600">Belum ada riwayat kunjungan tercatat pada periode ini.</p>
            </div>
            @endif

        </div>

    </div>

@endsection