@extends('layouts.app')

@section('title', 'Laporan & Analisis Data')

@section('content')

    <div class="space-y-6">
        
        <!-- Header Halaman -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <i class="bi bi-file-earmark-bar-graph-fill text-primary mr-3"></i> Laporan & Analisis
            </h1>
        </div>

        <!-- Kontainer Utama Laporan: Filter dan Hasil -->
        <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-100">
            
            <h2 class="text-xl font-semibold text-gray-800 mb-6 border-b pb-3">⚙️ Generator Laporan Kustom</h2>

            <!-- Area Filter Laporan -->
            <form action="#" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    
                    <!-- Filter Periode (Mulai) -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                        <input type="date" id="start_date" name="start_date" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition-colors">
                    </div>

                    <!-- Filter Periode (Akhir) -->
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                        <input type="date" id="end_date" name="end_date" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition-colors">
                    </div>

                    <!-- Filter Jenis Laporan -->
                    <div>
                        <label for="report_type" class="block text-sm font-medium text-gray-700 mb-1">Jenis Laporan</label>
                        <select id="report_type" name="report_type" class="w-full p-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary transition-colors">
                            <option value="kunjungan">Rekap Kunjungan (Harian/Bulanan)</option>
                            <option value="diagnosa">10 Besar Diagnosa</option>
                            <option value="obat">Penggunaan Obat</option>
                            <option value="dokter">Performa Dokter</option>
                        </select>
                    </div>

                    <!-- Filter Spesifik (misalnya, Dokter) -->
                    <div>
                        <label for="filter_specific" class="block text-sm font-medium text-gray-700 mb-1">Filter Spesifik</label>
                        <select id="filter_specific" name="filter_specific" class="w-full p-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary transition-colors">
                            <option value="">Semua Dokter/Poli</option>
                            <option value="D001">Dr. Rian Hidayat (Umum)</option>
                            <option value="D002">Drg. Amelia Putri (Gigi)</option>
                        </select>
                    </div>

                </div>

                <!-- Tombol Aksi -->
                <div class="pt-4 flex justify-end space-x-3">
                    <button type="submit" class="bg-primary hover:bg-secondary text-white font-semibold py-2 px-5 rounded-xl transition-colors duration-300 flex items-center shadow-md">
                        <i class="bi bi-funnel-fill mr-2"></i> Tampilkan Laporan
                    </button>
                    <button type="button" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-5 rounded-xl transition-colors duration-300 flex items-center shadow-md">
                        <i class="bi bi-file-earmark-excel-fill mr-2"></i> Ekspor (Excel/PDF)
                    </button>
                </div>
            </form>

            <hr class="my-8 border-gray-200">

            <!-- Area Hasil Laporan -->
            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="bi bi-clipboard-data text-primary mr-2"></i> Hasil Laporan
            </h2>
            <p class="text-sm text-gray-600 mb-4">Laporan: **Rekap Kunjungan** | Periode: 1 Okt 2025 - 31 Okt 2025</p>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Ringkasan Kunjungan -->
                <div class="bg-blue-50 p-4 rounded-xl shadow-inner border border-blue-200">
                    <p class="text-sm font-medium text-blue-800">Total Kunjungan</p>
                    <p class="text-2xl font-bold text-blue-900">450</p>
                </div>
                <!-- Ringkasan Pasien Baru -->
                <div class="bg-green-50 p-4 rounded-xl shadow-inner border border-green-200">
                    <p class="text-sm font-medium text-green-800">Pasien Baru</p>
                    <p class="text-2xl font-bold text-green-900">85</p>
                </div>
                <!-- Ringkasan Rata-rata Kunjungan Harian -->
                <div class="bg-yellow-50 p-4 rounded-xl shadow-inner border border-yellow-200">
                    <p class="text-sm font-medium text-yellow-800">Rata-rata Kunjungan/Hari</p>
                    <p class="text-2xl font-bold text-yellow-900">14.5</p>
                </div>
            </div>

            <!-- Tabel Detail Laporan -->
            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Tanggal
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Poli Umum
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Poli Gigi
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Total Harian
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        
                        {{-- Data Dummy Laporan --}}
                        @php
                            $dummyReport = [
                                ['date' => '2025-10-29', 'umum' => 15, 'gigi' => 5, 'total' => 20],
                                ['date' => '2025-10-30', 'umum' => 18, 'gigi' => 7, 'total' => 25],
                                ['date' => '2025-10-31', 'umum' => 12, 'gigi' => 3, 'total' => 15],
                            ];
                        @endphp

                        @forelse($dummyReport as $row)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                {{ \Carbon\Carbon::parse($row['date'])->isoFormat('D MMMM YYYY') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $row['umum'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $row['gigi'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-primary">
                                {{ $row['total'] }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                Silakan pilih periode dan jenis laporan untuk menampilkan data.
                            </td>
                        </tr>
                        @endforelse
                        
                    </tbody>
                </table>
            </div>
            
        </div>

    </div>

@endsection