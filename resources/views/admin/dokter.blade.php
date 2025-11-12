@extends('layouts.app')

@section('title', 'Data Dokter')

@section('content')

    <div class="space-y-6">
        
        <!-- Header Halaman -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <i class="bi bi-person-badge-fill text-primary mr-3"></i> Manajemen Data Dokter
            </h1>
            
            <a href="#" class="bg-primary hover:bg-secondary text-white font-semibold py-2 px-5 rounded-xl transition-colors duration-300 flex items-center shadow-md hover:shadow-lg">
                <i class="bi bi-person-plus-fill mr-2"></i> Tambah Data Dokter
            </a>
        </div>

        <!-- Kontainer Utama Tabel dan Filter -->
        <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-100">
            
            <!-- Area Filter dan Pencarian -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0">
                
                <!-- Search Bar -->
                <div class="w-full md:w-1/3">
                    <div class="relative">
                        <input type="text" placeholder="Cari berdasarkan Nama, NIP, atau Spesialisasi..." 
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                            aria-label="Cari Dokter">
                        <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>

                <!-- Filter Spesialisasi -->
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600 hidden sm:block">Filter Spesialisasi:</span>
                    
                    <select class="p-2 border border-gray-300 rounded-xl text-sm focus:ring-primary focus:border-primary transition-colors">
                        <option>Semua Spesialisasi</option>
                        <option>Umum</option>
                        <option>Gigi</option>
                        <option>Anak</option>
                    </select>
                </div>
            </div>

            <!-- Tabel Data Dokter -->
            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-12">
                                No.
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Nama Dokter
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden sm:table-cell">
                                NIP / Email
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden lg:table-cell">
                                Spesialisasi
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Jadwal Praktik
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-32">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        
                        {{-- Data Dummy --}}
                        @php
                            $dummyDoctors = [
                                ['id' => 1, 'name' => 'Dr. Rian Hidayat', 'nip' => 'D001', 'email' => 'rian@puskesmas.id', 'specialty' => 'Umum', 'schedule' => 'Senin-Jumat, 08:00 - 14:00'],
                                ['id' => 2, 'name' => 'Drg. Amelia Putri', 'nip' => 'D002', 'email' => 'amelia@puskesmas.id', 'specialty' => 'Gigi', 'schedule' => 'Selasa & Kamis, 09:00 - 12:00'],
                                ['id' => 3, 'name' => 'Dr. Santi Dewi', 'nip' => 'D003', 'email' => 'santi@puskesmas.id', 'specialty' => 'Anak', 'schedule' => 'Rabu & Jumat, 13:00 - 17:00'],
                            ];
                        @endphp

                        {{-- Loop data Dokter --}}
                        @forelse($dummyDoctors as $index => $doctor)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">{{ $doctor['name'] }}</div>
                                <div class="text-xs text-gray-500 capitalize">{{ $doctor['specialty'] }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">
                                <div class="text-sm text-gray-800 font-mono">{{ $doctor['nip'] }}</div>
                                <div class="text-xs text-gray-500">{{ $doctor['email'] }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap hidden lg:table-cell">
                                <span class="bg-primary/10 text-primary text-xs font-medium px-2 py-1 rounded-full">
                                    {{ $doctor['specialty'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-normal text-sm text-gray-700">
                                {{ $doctor['schedule'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-center space-x-1.5">
                                    {{-- Tombol Lihat Detail/Jadwal --}}
                                    <button title="Lihat Detail & Jadwal" class="text-primary hover:text-secondary p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-person-vcard text-lg"></i>
                                    </button>
                                    {{-- Tombol Edit --}}
                                    <button title="Edit Data Dokter" class="text-yellow-600 hover:text-yellow-800 p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-pencil-square text-lg"></i>
                                    </button>
                                    {{-- Tombol Delete --}}
                                    <button title="Hapus Dokter" class="text-red-600 hover:text-red-800 p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-trash text-lg"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                Tidak ada data dokter yang ditemukan.
                            </td>
                        </tr>
                        @endforelse
                        
                    </tbody>
                </table>
            </div>
            
            <!-- Area Pagination (Placeholder) -->
            <div class="mt-6 flex justify-between items-center">
                <p class="text-sm text-gray-600 hidden sm:block">Menampilkan 1-10 dari 12 Dokter</p>
                <div class="flex space-x-2 ml-auto">
                    <button class="px-3 py-1 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors disabled:opacity-50" disabled>
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button class="px-3 py-1 border border-primary bg-primary text-white rounded-lg transition-colors">
                        1
                    </button>
                    <button class="px-3 py-1 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                        2
                    </button>
                    <button class="px-3 py-1 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>

        </div>

    </div>

@endsection