@extends('layouts.app')

@section('title', 'Data Obat & Farmasi')

@section('content')

    <div class="space-y-6">
        
        <!-- Header Halaman -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <i class="bi bi-capsule-fill text-primary mr-3"></i> Manajemen Data Obat
            </h1>
            
            <a href="#" class="bg-primary hover:bg-secondary text-white font-semibold py-2 px-5 rounded-xl transition-colors duration-300 flex items-center shadow-md hover:shadow-lg">
                <i class="bi bi-plus-lg mr-2"></i> Tambah Obat Baru
            </a>
        </div>

        <!-- Kontainer Utama Tabel dan Filter -->
        <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-100">
            
            <!-- Area Filter dan Pencarian -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0">
                
                <!-- Search Bar -->
                <div class="w-full md:w-1/3">
                    <div class="relative">
                        <input type="text" placeholder="Cari berdasarkan Nama, Kode, atau Kategori..." 
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                            aria-label="Cari Obat">
                        <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>

                <!-- Filter Kategori dan Stok -->
                <div class="flex items-center space-x-4">
                    
                    <select class="p-2 border border-gray-300 rounded-xl text-sm focus:ring-primary focus:border-primary transition-colors">
                        <option>Semua Kategori</option>
                        <option>Analgesik</option>
                        <option>Antibiotik</option>
                        <option>Suplemen</option>
                    </select>

                    <select class="p-2 border border-gray-300 rounded-xl text-sm focus:ring-primary focus:border-primary transition-colors hidden sm:block">
                        <option>Semua Stok</option>
                        <option>Stok Rendah (&lt; 100)</option>
                        <option>Stok Aman</option>
                    </select>
                </div>
            </div>

            <!-- Tabel Data Obat -->
            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-12">
                                Kode
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Nama Obat
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden sm:table-cell">
                                Kategori
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Stok
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider hidden md:table-cell">
                                Harga Satuan
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-32">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        
                        {{-- Data Dummy --}}
                        @php
                            $dummyMedications = [
                                ['code' => 'OBT001', 'name' => 'Paracetamol 500mg', 'category' => 'Analgesik', 'stock' => 1500, 'unit_price' => 2000, 'exp' => '2027-12-31'],
                                ['code' => 'OBT002', 'name' => 'Amoxicillin 250mg', 'category' => 'Antibiotik', 'stock' => 350, 'unit_price' => 5500, 'exp' => '2026-06-15'],
                                ['code' => 'OBT003', 'name' => 'Sirup Batuk Anak', 'category' => 'Obat Bebas', 'stock' => 50, 'unit_price' => 18000, 'exp' => '2025-02-28'],
                                ['code' => 'OBT004', 'name' => 'Vitamin C 100mg', 'category' => 'Suplemen', 'stock' => 2000, 'unit_price' => 1500, 'exp' => '2028-01-01'],
                            ];
                        @endphp

                        {{-- Loop data Obat --}}
                        @forelse($dummyMedications as $medication)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-primary">
                                {{ $medication['code'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">{{ $medication['name'] }}</div>
                                <div class="text-xs text-gray-500">Kedaluwarsa: {{ \Carbon\Carbon::parse($medication['exp'])->isoFormat('D MMM YYYY') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden sm:table-cell">
                                <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2 py-1 rounded-full">
                                    {{ $medication['category'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                @php
                                    $stock = $medication['stock'];
                                    $stockClass = 'bg-green-100 text-green-800';
                                    if ($stock < 100) {
                                        $stockClass = 'bg-red-100 text-red-800 font-bold';
                                    } elseif ($stock < 500) {
                                        $stockClass = 'bg-yellow-100 text-yellow-800';
                                    }
                                @endphp
                                <span class="{{ $stockClass }} px-3 py-1 rounded-full text-xs font-semibold">
                                    {{ number_format($stock, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-gray-900 hidden md:table-cell">
                                Rp{{ number_format($medication['unit_price'], 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-center space-x-1.5">
                                    {{-- Tombol View (Lihat Detail) --}}
                                    <button title="Lihat Detail Obat" class="text-primary hover:text-secondary p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-box-seam text-lg"></i>
                                    </button>
                                    {{-- Tombol Edit --}}
                                    <button title="Edit Data Obat" class="text-yellow-600 hover:text-yellow-800 p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-pencil-square text-lg"></i>
                                    </button>
                                    {{-- Tombol Delete --}}
                                    <button title="Hapus Obat" class="text-red-600 hover:text-red-800 p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-trash text-lg"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                Tidak ada data obat yang ditemukan.
                            </td>
                        </tr>
                        @endforelse
                        
                    </tbody>
                </table>
            </div>
            
            <!-- Area Pagination (Placeholder) -->
            <div class="mt-6 flex justify-between items-center">
                <p class="text-sm text-gray-600 hidden sm:block">Total data: 125 jenis obat</p>
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