@extends('layouts.app')

@section('title', 'Manajemen Data Pasien')

@section('content')

    <div class="space-y-6">
        
        <!-- Header Halaman dan Tombol Aksi -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <i class="bi bi-people-fill text-yellow-600 mr-3"></i> Manajemen Data Pasien
            </h1>
            
            {{-- Tombol utama Petugas: Tambah Pasien Baru --}}
            <a href="#" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-5 rounded-xl transition-colors duration-300 flex items-center shadow-md hover:shadow-lg">
                <i class="bi bi-person-plus-fill mr-2"></i> Tambah Pasien Baru
            </a>
        </div>

        <!-- Kontainer Utama Tabel dan Filter -->
        <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-100">
            
            <!-- Area Filter dan Pencarian -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0">
                
                <!-- Search Bar -->
                <div class="w-full md:w-1/3">
                    <div class="relative">
                        <input type="text" placeholder="Cari berdasarkan Nama, ID, NIK, atau BPJS..." 
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all"
                            aria-label="Cari Pasien">
                        <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>

                <!-- Filter dan Pagination Status -->
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600 hidden sm:block">Menampilkan 1-10 dari 1250 pasien</span>
                    
                    {{-- Filter Status BPJS --}}
                    <select class="p-2 border border-gray-300 rounded-xl text-sm focus:ring-yellow-500 focus:border-yellow-500 transition-colors">
                        <option>Semua Status Pembayaran</option>
                        <option>BPJS Aktif</option>
                        <option>BPJS Non-Aktif/Verifikasi</option>
                        <option>Umum/Tunai</option>
                    </select>
                </div>
            </div>

            <!-- Tabel Data Pasien -->
            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-12">
                                ID Pasien
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Nama Pasien
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden sm:table-cell">
                                NIK
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden lg:table-cell">
                                Tgl. Lahir / JK
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider hidden md:table-cell">
                                Status BPJS
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-40">
                                Aksi & Kunjungan
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        
                        {{-- Data Dummy --}}
                        @php
                            $dummyPatients = [
                                ['id' => 'P1029', 'name' => 'Budi Santoso', 'nik' => '320101...', 'dob' => '15/05/1990', 'gender' => 'L', 'bpjs_status' => 'Aktif', 'bpjs_no' => '0001234567890'],
                                ['id' => 'P1105', 'name' => 'Siti Nurhaliza', 'nik' => '320102...', 'dob' => '22/11/1985', 'gender' => 'P', 'bpjs_status' => 'Verifikasi', 'bpjs_no' => '0001987654321'],
                                ['id' => 'P0981', 'name' => 'Joko Susilo', 'nik' => '320103...', 'dob' => '01/01/2000', 'gender' => 'L', 'bpjs_status' => 'Umum', 'bpjs_no' => null],
                                ['id' => 'P1251', 'name' => 'Rina Dewi', 'nik' => '320104...', 'dob' => '10/08/1975', 'gender' => 'P', 'bpjs_status' => 'Aktif', 'bpjs_no' => '0001556677889'],
                            ];
                        @endphp

                        @foreach($dummyPatients as $patient)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-yellow-600">
                                {{ $patient['id'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">{{ $patient['name'] }}</div>
                                <div class="text-xs text-gray-500">{{ $patient['bpjs_no'] ?? 'NON-BPJS' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden sm:table-cell">
                                {{ $patient['nik'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden lg:table-cell">
                                {{ $patient['dob'] }} ({{ $patient['gender'] }})
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm hidden md:table-cell">
                                @if($patient['bpjs_status'] == 'Aktif')
                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded-full">BPJS Aktif</span>
                                @elseif($patient['bpjs_status'] == 'Verifikasi')
                                    <span class="bg-red-100 text-red-800 text-xs font-medium px-2 py-1 rounded-full">Verifikasi Wajib</span>
                                @else
                                    <span class="bg-gray-200 text-gray-800 text-xs font-medium px-2 py-1 rounded-full">Umum</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-center space-x-1.5">
                                    
                                    {{-- Tombol Edit Data Dasar (Petugas) --}}
                                    <button title="Edit Data Dasar Pasien" class="text-primary hover:text-secondary p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-pencil-square text-lg"></i>
                                    </button>
                                    
                                    {{-- Tombol Daftarkan Kunjungan --}}
                                    <button title="Daftarkan Kunjungan Baru" class="text-blue-600 hover:text-blue-800 p-1.5 rounded-full hover:bg-blue-100 transition-colors">
                                        <i class="bi bi-calendar-plus-fill text-lg"></i>
                                    </button>

                                    {{-- Tombol Lihat Detail/Riwayat --}}
                                    <button title="Lihat Riwayat Kunjungan" class="text-gray-600 hover:text-gray-800 p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-eye text-lg"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        
                    </tbody>
                </table>
            </div>
            
            <!-- Area Pagination (Placeholder) -->
            <div class="mt-6 flex justify-between items-center">
                <p class="text-sm text-gray-600">Total data: 1250</p>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors disabled:opacity-50" disabled>
                        &laquo; Sebelumnya
                    </button>
                    <button class="px-3 py-1 border border-yellow-500 bg-yellow-500 text-white rounded-lg transition-colors">
                        1
                    </button>
                    <button class="px-3 py-1 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                        2
                    </button>
                    <button class="px-3 py-1 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                        Selanjutnya &raquo;
                    </button>
                </div>
            </div>

        </div>

    </div>

@endsection