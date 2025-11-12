@extends('layouts.app')

@section('title', 'Antrian Resep Farmasi')

@section('content')

    <div class="space-y-6">
        
        <header class="bg-white p-6 rounded-2xl shadow-xl border-l-8 border-primary border-t border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center mb-1">
                    <i class="bi bi-prescription2 text-primary mr-3"></i> Antrian Resep Farmasi
                </h1>
                <p class="text-lg text-gray-600">Daftar resep yang perlu disiapkan dan divalidasi.</p>
            </div>
            
            <div class="flex items-center space-x-3">
                <label for="filter-status" class="text-sm font-medium text-gray-700">Filter Status:</label>
                <select id="filter-status" class="p-2 border border-gray-300 rounded-xl text-sm focus:ring-primary focus:border-primary transition-colors">
                    <option>Hanya Menunggu (5)</option>
                    <option>Sedang Diproses (1)</option>
                    <option>Semua Antrian Aktif</option>
                </select>
            </div>
        </header>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 flex items-center justify-between border border-gray-100">
                <div>
                    <p class="text-sm font-medium text-gray-500">Menunggu Diproses</p>
                    <p class="text-4xl font-bold text-red-600 mt-1">5</p>
                </div>
                <div class="bg-red-500/10 p-3 rounded-xl text-red-600 flex items-center justify-center">
                    <i class="bi bi-receipt-cutoff text-3xl"></i>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 flex items-center justify-between border border-gray-100">
                <div>
                    <p class="text-sm font-medium text-gray-500">Resep Selesai Hari Ini</Vp>
                    <p class="text-4xl font-bold text-green-600 mt-1">22</p>
                </div>
                <div class="bg-green-500/10 p-3 rounded-xl text-green-600 flex items-center justify-center">
                    <i class="bi bi-check-all text-3xl"></i>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 flex items-center justify-between border border-gray-100">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Resep Bulan Ini</p>
                    <p class="text-4xl font-bold text-gray-900 mt-1">350</p>
                </div>
                <div class="bg-blue-500/10 p-3 rounded-xl text-blue-600 flex items-center justify-center">
                    <i class="bi bi-calendar-range-fill text-3xl"></i>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 flex items-center justify-between border border-gray-100">
                <div>
                    <p class="text-sm font-medium text-gray-500">Stok Obat Kritis</p>
                    <p class="text-4xl font-bold text-orange-600 mt-1">7</p>
                </div>
                <div class="bg-orange-500/10 p-3 rounded-xl text-orange-600 flex items-center justify-center">
                    <i class="bi bi-exclamation-triangle-fill text-3xl"></i>
                </div>
            </div>
        </div>

        <div x-data="{ activeTab: 'menunggu' }" class="bg-white shadow-xl rounded-2xl border border-gray-100">
            
            <div class="border-b border-gray-200">
                <nav class="flex space-x-4 p-4" aria-label="Tabs">
                    <button @click="activeTab = 'menunggu'" 
                            :class="activeTab === 'menunggu' ? 'border-primary text-primary bg-primary/10' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                            class="py-2 px-4 inline-flex items-center font-medium text-sm rounded-xl border-b-2 transition-colors duration-200">
                        <i class="bi bi-clock-history mr-2"></i> Antrian Menunggu (5)
                    </button>
                    <button @click="activeTab = 'selesai'" 
                            :class="activeTab === 'selesai' ? 'border-primary text-primary bg-primary/10' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                            class="py-2 px-4 inline-flex items-center font-medium text-sm rounded-xl border-b-2 transition-colors duration-200">
                        <i class="bi bi-check-circle-fill mr-2"></i> Riwayat Selesai Hari Ini
                    </button>
                </nav>
            </div>

            <div class="p-6">
                
                {{-- TAB 1: ANTRIAN MENUNGGU --}}
                <div x-show="activeTab === 'menunggu'" class="space-y-4">
                    
                    {{-- Resep 1 (Sedang Diproses) --}}
                    <div classs="p-5 bg-yellow-50/50 rounded-lg shadow-sm border-l-4 border-yellow-500 border border-gray-200">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center p-5">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Pasien: Joko Susilo (P0981)</h3>
                                <p class="text-sm text-gray-600 mt-1">Dokter Peresep: **Dr. Santi (Poli Anak)**</t>
                                <p class="text-xs text-gray-500">Masuk: 10:55 (5 menit lalu) | Status: <span class="font-semibold text-yellow-700">Sedang Disiapkan (oleh: Apoteker Budi)</span></p>
                            </div>
                            <a href="#" class="mt-3 md:mt-0 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded-xl transition-colors duration-300 flex items-center shadow-md">
                                <i class="bi bi-eye-fill mr-2"></i> Lanjutkan Siapkan Resep
                            </a>
                        </div>
                    </div>

                    {{-- Resep 2 (Menunggu) --}}
                    <div classs="p-5 bg-white rounded-lg shadow-sm border-l-4 border-red-500 border border-gray-200">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center p-5">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Pasien: Budi Santoso (P1029)</h3>
                                <p class="text-sm text-gray-600 mt-1">Dokter Peresep: **Dr. Rian Setiawan, Sp.PD**</p>
                                <p class="text-xs text-gray-500">Masuk: 10:45 (15 menit lalu) | Status: <span class="font-semibold text-red-600">Menunggu</span></p>
                            </div>
                            <a href="#" class="mt-3 md:mt-0 bg-primary hover:bg-secondary text-white font-semibold py-2 px-4 rounded-xl transition-colors duration-300 flex items-center shadow-md">
                                <i class="bi bi-play-circle-fill mr-2"></i> Mulai Siapkan Resep
                            </a>
                        </div>
                        <div class="border-t border-gray-100 p-5 bg-gray-50/50">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Detail Item Resep:</h4>
                            <ul class="list-disc list-inside text-sm text-gray-800 space-y-1">
                                <li>Paracetamol 500mg (10 Tablet) - <span class="text-green-600 font-medium">Stok: 1200 (Aman)</span></li>
                                <li>Ciprofloksasin 250mg (15 Kapsul) - <span class="text-red-600 font-medium">Stok: 5 (Kritis! Perlu Konfirmasi)</span></li>
                                <li>Vitamin B Complex (10 Tablet) - <span class="text-green-600 font-medium">Stok: 500 (Aman)</span></li>
                            </ul>
                        </div>
                    </div>

                    {{-- Resep 3 (Menunggu) --}}
                    <div classs="p-5 bg-white rounded-lg shadow-sm border-l-4 border-red-500 border border-gray-200">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center p-5">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Pasien: Siti Nurhaliza (P1105)</h3>
                                <p class="text-sm text-gray-600 mt-1">Dokter Peresep: **Dr. Amelia (Poli Gigi)**</p>
                                <p class="text-xs text-gray-500">Masuk: 10:30 (30 menit lalu) | Status: <span class="font-semibold text-red-600">Menunggu</span></p>
                            </div>
                            <a href="#" class="mt-3 md:mt-0 bg-primary hover:bg-secondary text-white font-semibold py-2 px-4 rounded-xl transition-colors duration-300 flex items-center shadow-md">
                                <i class="bi bi-play-circle-fill mr-2"></i> Mulai Siapkan Resep
                            </a>
                        </div>
                        <div class="border-t border-gray-100 p-5 bg-gray-50/50">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Detail Item Resep:</h4>
                            <ul class="list-disc list-inside text-sm text-gray-800 space-y-1">
                                <li>Asam Mefenamat 500mg (10 Tablet) - <span class="text-green-600 font-medium">Stok: 800 (Aman)</span></li>
                            </ul>
                        </div>
                    </div>
                </div>

                
                {{-- TAB 2: RIWAYAT SELESAI --}}
                <div x-show="activeTab === 'selesai'" class="space-y-6" style="display: none;">
                    
                    <div class="overflow-x-auto rounded-xl border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID Resep</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Pasien</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden sm:table-cell">Dokter Peresep</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden md:table-cell">Petugas Farmasi</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Waktu Selesai</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">RSP1009</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">Bambang (P0822)</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden sm:table-cell">Dr. Rian Setiawan</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden md:table-cell">Apoteker Budi</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">10:15 (Pagi ini)</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <button class="text-primary hover:text-secondary p-1.5 rounded-full hover:bg-gray-100 transition-colors" title="Lihat Detail Resep">
                                            <i class="bi bi-eye text-lg"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">RSP1008</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">Lena Sari (P0911)</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden sm:table-cell">Dr. Rian Setiawan</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden md:table-cell">Petugas Ani</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">09:45 (Pagi ini)</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <button class="text-primary hover:text-secondary p-1.5 rounded-full hover:bg-gray-100 transition-colors" title="Liat Detail Resep">
                                            <i class="bi bi-eye text-lg"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    </div>

@endsection 