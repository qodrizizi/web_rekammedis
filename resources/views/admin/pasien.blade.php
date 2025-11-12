@extends('layouts.app')

@section('title', 'Data Pasien')

@section('content')

    <div class="space-y-6">
        
        <!-- Header Halaman dan Tombol Aksi -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <i class="bi bi-people-fill text-primary mr-3"></i> Manajemen Data Pasien
            </h1>
            
            <a href="#" class="bg-primary hover:bg-secondary text-white font-semibold py-2 px-5 rounded-xl transition-colors duration-300 flex items-center shadow-md hover:shadow-lg">
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
                        <input type="text" placeholder="Cari berdasarkan Nama, NIK, atau BPJS..." 
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                            aria-label="Cari Pasien">
                        <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>

                <!-- Filter dan Pagination Status -->
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600 hidden sm:block">Menampilkan 1-10 dari 1250 pasien</span>
                    
                    <select class="p-2 border border-gray-300 rounded-xl text-sm focus:ring-primary focus:border-primary transition-colors">
                        <option>Semua Jenis Kelamin</option>
                        <option>Laki-laki</option>
                        <option>Perempuan</option>
                    </select>
                </div>
            </div>

            <!-- Tabel Data Pasien -->
            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-12">
                                No.
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Nama Pasien
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden sm:table-cell">
                                NIK
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden lg:table-cell">
                                Tgl. Lahir / Jenis Kelamin
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden md:table-cell">
                                No. BPJS
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-32">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        
                        {{-- Data Dummy --}}
                        @php
                            $dummyPatients = [
                                ['name' => 'Budi Santoso', 'email' => 'budi@mail.com', 'nik' => '3201019876543210', 'dob' => '15/05/1990', 'gender' => 'L', 'bpjs' => '0001234567890'],
                                ['name' => 'Siti Aisyah', 'email' => 'siti@mail.com', 'nik' => '3201021234567890', 'dob' => '22/11/1985', 'gender' => 'P', 'bpjs' => '0001987654321'],
                                ['name' => 'Joko Susilo', 'email' => 'joko@mail.com', 'nik' => '3201030987654321', 'dob' => '01/01/2000', 'gender' => 'L', 'bpjs' => null],
                                ['name' => 'Dewi Puspita', 'email' => 'dewi@mail.com', 'nik' => '3201041122334455', 'dob' => '10/08/1975', 'gender' => 'P', 'bpjs' => '0001556677889'],
                            ];
                        @endphp

                        @foreach($dummyPatients as $index => $patient)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">{{ $patient['name'] }}</div>
                                <div class="text-xs text-gray-500">{{ $patient['email'] }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden sm:table-cell">
                                {{ $patient['nik'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden lg:table-cell">
                                {{ $patient['dob'] }} ({{ $patient['gender'] }})
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden md:table-cell">
                                <span class="{{ $patient['bpjs'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} text-xs font-medium px-2 py-1 rounded-full">
                                    {{ $patient['bpjs'] ?? 'NON-BPJS' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-center space-x-1.5">
                                    {{-- Tombol View (Lihat Detail) --}}
                                    <button title="Lihat Detail Pasien" class="text-primary hover:text-secondary p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-eye text-lg"></i>
                                    </button>
                                    {{-- Tombol Edit --}}
                                    <button title="Edit Data Pasien" class="text-yellow-600 hover:text-yellow-800 p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-pencil-square text-lg"></i>
                                    </button>
                                    {{-- Tombol Delete --}}
                                    <button title="Hapus Pasien" class="text-red-600 hover:text-red-800 p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-trash text-lg"></i>
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
                    <button class="px-3 py-1 border border-primary bg-primary text-white rounded-lg transition-colors">
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