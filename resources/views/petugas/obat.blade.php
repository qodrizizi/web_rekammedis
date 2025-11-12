@extends('layouts.app')

@section('title', 'Manajemen Stok Obat & Farmasi')

@section('content')

    <div class="space-y-6">
        
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <i class="bi bi-capsule-pill text-red-600 mr-3"></i> Manajemen Stok Obat
            </h1>
            
            <div class="flex space-x-3">
                {{-- Tombol utama: Tambah Stok Baru (Barang Masuk) --}}
                <a href="#" class="bg-primary hover:bg-secondary text-white font-semibold py-2 px-5 rounded-xl transition-colors duration-300 flex items-center shadow-md hover:shadow-lg">
                    <i class="bi bi-box-arrow-in-right mr-2"></i> Tambah Stok Baru
                </a>
                {{-- Tombol Aksi: Lihat Riwayat Log --}}
                <a href="#" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-5 rounded-xl transition-colors duration-300 flex items-center shadow-md">
                    <i class="bi bi-clock-history mr-2"></i> Log Transaksi
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 flex items-center justify-between border border-gray-100">
                <div>
                    <p class="text-sm font-medium text-gray-500">Item Stok Kritis</p>
                    <p class="text-3xl font-bold text-red-600 mt-1">7</p>
                </div>
                <div class="bg-red-500/10 p-3 rounded-xl text-red-600 flex items-center justify-center">
                    <i class="bi bi-exclamation-triangle-fill text-3xl"></i>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 flex items-center justify-between border border-gray-100">
                <div>
                    <p class="text-sm font-medium text-gray-500">Mendekati Kadaluarsa (60 Hari)</p>
                    <p class="text-3xl font-bold text-orange-600 mt-1">15</p>
                </div>
                <div class="bg-orange-500/10 p-3 rounded-xl text-orange-600 flex items-center justify-center">
                    <i class="bi bi-calendar-x-fill text-3xl"></i>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 flex items-center justify-between border border-gray-100">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Jenis Obat Aktif</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">145</p>
                </div>
                <div class="bg-blue-500/10 p-3 rounded-xl text-blue-600 flex items-center justify-center">
                    <i class="bi bi-database-fill text-3xl"></i>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 flex items-center justify-between border border-gray-100">
                <div>
                    <p class="text-sm font-medium text-gray-500">Resep Menunggu Disiapkan</p>
                    <p class="text-3xl font-bold text-yellow-600 mt-1">5</p>
                </div>
                <div class="bg-yellow-500/10 p-3 rounded-xl text-yellow-600 flex items-center justify-center">
                    <i class="bi bi-receipt-cutoff text-3xl"></i>
                </div>
            </div>
        </div>


        <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-100">
            
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0">
                
                <div class="w-full md:w-1/3">
                    <div class="relative">
                        <input type="text" placeholder="Cari berdasarkan Nama Obat, Kode, atau Kandungan..." 
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                            aria-label="Cari Obat">
                        <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600 hidden sm:block">Menampilkan 1-10 dari 145 jenis obat</span>
                    
                    {{-- Filter Status Stok --}}
                    <select class="p-2 border border-gray-300 rounded-xl text-sm focus:ring-primary focus:border-primary transition-colors">
                        <option>Semua Kategori</option>
                        <option>Stok Kritis</option>
                        <option>Akan Kadaluarsa</option>
                        <option>Obat Bebas</option>
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-16">
                                Kode
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Nama Obat & Satuan
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider hidden sm:table-cell">
                                Stok Saat Ini
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider hidden lg:table-cell">
                                Batas Stok (Minimum)
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider hidden md:table-cell">
                                Tgl. Kadaluarsa
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-32">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        
                        {{-- Data Dummy --}}
                        @php
                            $dummyDrugs = [
                                ['code' => 'PCL001', 'name' => 'Paracetamol 500mg', 'unit' => 'Tablet', 'stock' => 1200, 'min_stock' => 1000, 'expiry' => '2026-10-20'],
                                ['code' => 'AMX005', 'name' => 'Amoxicillin 250mg', 'unit' => 'Kapsul', 'stock' => 850, 'min_stock' => 1000, 'expiry' => '2027-01-15'],
                                ['code' => 'IBP002', 'name' => 'Ibuprofen 400mg', 'unit' => 'Tablet', 'stock' => 50, 'min_stock' => 100, 'expiry' => '2025-12-01'], // Kritis & Kadaluarsa dekat
                                ['code' => 'CPM010', 'name' => 'Chlorphenamine Maleate', 'unit' => 'Tablet', 'stock' => 3000, 'min_stock' => 500, 'expiry' => '2028-03-10'],
                            ];
                        @endphp

                        @foreach($dummyDrugs as $drug)
                        <tr class="hover:bg-gray-50 transition-colors 
                            @if($drug['stock'] < $drug['min_stock'] || strtotime($drug['expiry']) < strtotime('+60 days')) bg-red-50/50 @endif">
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $drug['code'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">{{ $drug['name'] }}</div>
                                <div class="text-xs text-gray-500">{{ $drug['unit'] }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold 
                                @if($drug['stock'] < $drug['min_stock']) text-red-600 @else text-green-600 @endif hidden sm:table-cell">
                                {{ number_format($drug['stock']) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 hidden lg:table-cell">
                                {{ number_format($drug['min_stock']) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm hidden md:table-cell">
                                @if(strtotime($drug['expiry']) < strtotime('+60 days'))
                                    <span class="bg-red-100 text-red-800 text-xs font-medium px-2 py-1 rounded-full">
                                        {{ $drug['expiry'] }} (Segera Buang/Pesan)
                                    </span>
                                @else
                                    <span class="text-gray-700 text-xs font-medium">{{ $drug['expiry'] }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-center space-x-1.5">
                                    {{-- Tombol Edit Data/Harga --}}
                                    <button title="Edit Data Obat" class="text-yellow-600 hover:text-yellow-800 p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-pencil-square text-lg"></i>
                                    </button>
                                    {{-- Tombol Detail Stok/Riwayat --}}
                                    <button title="Detail Stok & Mutasi" class="text-primary hover:text-secondary p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-box-seam text-lg"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        
                    </tbody>
                </table>
            </div>
            
            <div class="mt-6 flex justify-between items-center">
                <p class="text-sm text-gray-600">Total jenis: 145</p>
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