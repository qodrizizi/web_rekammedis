@extends('layouts.app')

@section('title', 'Data Rekam Medis')

@section('content')

    <div class="space-y-6">
        
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <i class="bi bi-clipboard2-pulse-fill text-primary mr-3"></i> Data Rekam Medis
            </h1>
            
            <a href="#" class="bg-primary hover:bg-secondary text-white font-semibold py-2 px-5 rounded-xl transition-colors duration-300 flex items-center shadow-md hover:shadow-lg">
                <i class="bi bi-plus-lg mr-2"></i> Tambah Rekam Medis Baru
            </a>
        </div>

        <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-100">
            
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0">
                
                <div class="w-full md:w-1/3">
                    <div class="relative">
                        <input type="text" placeholder="Cari berdasarkan Pasien, Dokter, atau Diagnosa..." 
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                            aria-label="Cari Rekam Medis">
                        <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600 hidden sm:block">Filter berdasarkan:</span>
                    
                    <select class="p-2 border border-gray-300 rounded-xl text-sm focus:ring-primary focus:border-primary transition-colors">
                        <option>Semua Poli</option>
                        <option>Poli Umum</option>
                        <option>Poli Gigi</option>
                        <option>Poli Anak</option>
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-12">
                                Tgl.
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Pasien
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden sm:table-cell">
                                Dokter / Poli
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Diagnosa Utama
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-32">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        
                        {{-- Data Dummy --}}
                        @php
                            $dummyRecords = [
                                ['id' => 1, 'date' => '08 Nov 2025', 'patient' => 'Budi Santoso', 'doctor' => 'Dr. Rian', 'clinic' => 'Poli Umum', 'diagnosis' => 'ISPA Ringan'],
                                ['id' => 2, 'date' => '07 Nov 2025', 'patient' => 'Siti Aisyah', 'doctor' => 'Dr. Amelia', 'clinic' => 'Poli Gigi', 'diagnosis' => 'Periodontitis Kronis'],
                                ['id' => 3, 'date' => '05 Nov 2025', 'patient' => 'Joko Susilo', 'doctor' => 'Dr. Santi', 'clinic' => 'Poli Anak', 'diagnosis' => 'Demam Berdarah (DHF)'],
                                ['id' => 4, 'date' => '01 Nov 2025', 'patient' => 'Dewi Puspita', 'doctor' => 'Dr. Rian', 'clinic' => 'Poli Umum', 'diagnosis' => 'Hipertensi Primer'],
                            ];
                        @endphp

                        {{-- Loop data rekam medis --}}
                        @forelse($dummyRecords as $record)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700">
                                {{ $record['date'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm font-semibold text-gray-900">{{ $record['patient'] }}</p>
                                <p class="text-xs text-gray-500">RM-{{ str_pad($record['id'], 5, '0', STR_PAD_LEFT) }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden sm:table-cell">
                                <div class="font-medium text-gray-800">{{ $record['doctor'] }}</div>
                                <div class="text-xs text-primary">{{ $record['clinic'] }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-normal text-sm text-gray-800 font-medium">
                                {{ $record['diagnosis'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-center space-x-1.5">
                                    {{-- Tombol View (Lihat Detail) --}}
                                    <button title="Lihat Detail Rekam Medis" class="text-primary hover:text-secondary p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-file-earmark-medical text-lg"></i>
                                    </button>
                                    {{-- Tombol Edit (Hanya jika diizinkan) --}}
                                    <button title="Edit Catatan" class="text-yellow-600 hover:text-yellow-800 p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-pencil-square text-lg"></i>
                                    </button>
                                    {{-- Tombol Delete (Hanya jika diizinkan) --}}
                                    <button title="Hapus Catatan" class="text-red-600 hover:text-red-800 p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-trash text-lg"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                Tidak ada data rekam medis yang ditemukan.
                            </td>
                        </tr>
                        @endforelse
                        
                    </tbody>
                </table>
            </div>
            
            <div class="mt-6 flex justify-center">
                <div class="flex space-x-2">
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
