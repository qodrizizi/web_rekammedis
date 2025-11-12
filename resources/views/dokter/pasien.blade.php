@extends('layouts.app')

@section('title', 'Data Pasien Saya')

@section('content')

    <div class="space-y-6">
        
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <i class="bi bi-people-fill text-primary mr-3"></i> Daftar Pasien Saya
            </h1>
            
            {{-- Tombol aksi untuk dokter difokuskan pada memulai pemeriksaan --}}
            <a href="#" class="bg-primary/90 hover:bg-primary text-white font-semibold py-2 px-5 rounded-xl transition-colors duration-300 flex items-center shadow-md hover:shadow-lg">
                <i class="bi bi-person-lines-fill mr-2"></i> Mulai Pemeriksaan Baru
            </a>
        </div>

        <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-100">
            
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0">
                
                <div class="w-full md:w-1/3">
                    <div class="relative">
                        <input type="text" placeholder="Cari berdasarkan Nama, ID Pasien, atau NIK..." 
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                            aria-label="Cari Pasien">
                        <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600 hidden sm:block">Menampilkan 1-10 dari 58 pasien</span>
                    
                    {{-- Filter yang lebih relevan untuk dokter --}}
                    <select class="p-2 border border-gray-300 rounded-xl text-sm focus:ring-primary focus:border-primary transition-colors">
                        <option>Semua Pasien</option>
                        <option>Pasien Baru Bulan Ini</option>
                        <option>Pasien Kontrol</option>
                    </select>
                </div>
            </div>

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
                                Tgl. Lahir
                            </th>
                            {{-- Kolom baru yang relevan untuk Dokter --}}
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden lg:table-cell">
                                Pemeriksaan Terakhir
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden md:table-cell">
                                Diagnosis Terakhir
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-32">
                                Aksi Klinis
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        
                        {{-- Data Dummy Pasien Dokter --}}
                        @php
                            $dummyPatients = [
                                ['id' => 'P1029', 'name' => 'Budi Santoso', 'gender' => 'L', 'dob' => '15/05/1990', 'last_check' => '10 Nov 2025', 'diagnosis' => 'Demam Tifoid'],
                                ['id' => 'P1105', 'name' => 'Siti Nurhaliza', 'gender' => 'P', 'dob' => '22/11/1985', 'last_check' => '25 Okt 2025', 'diagnosis' => 'Hipertensi'],
                                ['id' => 'P0981', 'name' => 'Joko Susilo', 'gender' => 'L', 'dob' => '01/01/2000', 'last_check' => '2 hari lalu', 'diagnosis' => 'Post Operasi App'],
                                ['id' => 'P1251', 'name' => 'Rina Dewi', 'gender' => 'P', 'dob' => '10/08/1975', 'last_check' => 'Baru', 'diagnosis' => 'N/A'],
                            ];
                        @endphp

                        @foreach($dummyPatients as $patient)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-primary">
                                {{ $patient['id'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">{{ $patient['name'] }}</div>
                                <div class="text-xs text-gray-500">({{ $patient['gender'] }})</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden sm:table-cell">
                                {{ $patient['dob'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden lg:table-cell">
                                <span class="{{ $patient['last_check'] == 'Baru' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600' }} text-xs font-medium px-2 py-1 rounded-full">
                                    {{ $patient['last_check'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden md:table-cell">
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2 py-1 rounded-full">
                                    {{ $patient['diagnosis'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-center space-x-1.5">
                                    {{-- Tombol Lihat Rekam Medis --}}
                                    <button title="Lihat Rekam Medis" class="text-primary hover:text-secondary p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-journal-medical text-lg"></i>
                                    </button>
                                    {{-- Tombol Mulai / Lanjutkan Pemeriksaan --}}
                                    <button title="Mulai Pemeriksaan Baru" class="text-blue-600 hover:text-blue-800 p-1.5 rounded-full hover:bg-blue-100 transition-colors">
                                        <i class="bi bi-person-check-fill text-lg"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        
                    </tbody>
                </table>
            </div>
            
            <div class="mt-6 flex justify-between items-center">
                <p class="text-sm text-gray-600">Total data: 58 pasien Anda</p>
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