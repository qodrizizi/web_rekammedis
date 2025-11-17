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
            <form action="{{ route('admin.laporan') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    
                    <!-- Filter Periode (Mulai) -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                        <input type="date" id="start_date" name="start_date" 
                            value="{{ $startDate->format('Y-m-d') }}"
                            required class="w-full p-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition-colors">
                    </div>

                    <!-- Filter Periode (Akhir) -->
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                        <input type="date" id="end_date" name="end_date" 
                            value="{{ $endDate->format('Y-m-d') }}"
                            required class="w-full p-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition-colors">
                    </div>

                    <!-- Filter Jenis Laporan -->
                    <div>
                        <label for="report_type" class="block text-sm font-medium text-gray-700 mb-1">Jenis Laporan</label>
                        <select id="report_type" name="report_type" onchange="this.form.submit()"
                            class="w-full p-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary transition-colors">
                            <option value="kunjungan" @selected($reportType == 'kunjungan')>Rekap Kunjungan Per Poli</option>
                            <option value="diagnosa" @selected($reportType == 'diagnosa')>10 Besar Diagnosa</option>
                            <option value="obat" @selected($reportType == 'obat')>Penggunaan Obat</option>
                        </select>
                    </div>

                    <!-- Filter Spesifik (Dokter, hanya relevan untuk kunjungan) -->
                    <div id="filter_specific_container" @if($reportType != 'kunjungan') style="display: none;" @endif>
                        <label for="filter_specific" class="block text-sm font-medium text-gray-700 mb-1">Filter Dokter</label>
                        <select id="filter_specific" name="filter_specific" class="w-full p-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary transition-colors">
                            <option value="">Semua Dokter</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}" @selected($filterSpecific == $doctor->id)>{{ $doctor->user->name ?? 'N/A' }} ({{ $doctor->spesialis }})</option>
                            @endforeach
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
            <p class="text-sm text-gray-600 mb-4">
                Laporan: **{{ ucwords(str_replace('_', ' ', $reportType)) }}** | 
                Periode: {{ $startDate->isoFormat('D MMM YYYY') }} - {{ $endDate->isoFormat('D MMM YYYY') }}
            </p>

            <!-- 1. RINGKASAN LAPORAN (Semua ada di sini) -->
            <div class="mb-8">
                @if($reportType == 'kunjungan')
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-blue-50 p-4 rounded-xl shadow-inner border border-blue-200">
                            <p class="text-sm font-medium text-blue-800">Total Kunjungan</p>
                            <p class="text-2xl font-bold text-blue-900">{{ number_format($summary['total_kunjungan'] ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-xl shadow-inner border border-green-200">
                            <p class="text-sm font-medium text-green-800">Pasien Baru (Periode Ini)</p>
                            <p class="text-2xl font-bold text-green-900">{{ number_format($summary['pasien_baru'] ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-xl shadow-inner border border-yellow-200">
                            <p class="text-sm font-medium text-yellow-800">Rata-rata Kunjungan/Hari</p>
                            <p class="text-2xl font-bold text-yellow-900">{{ $summary['rata_rata_harian'] ?? '0' }}</p>
                        </div>
                    </div>
                @elseif($reportType == 'diagnosa')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-red-50 p-4 rounded-xl shadow-inner border border-red-200">
                            <p class="text-sm font-medium text-red-800">Total Records Diproses</p>
                            <p class="text-2xl font-bold text-red-900">{{ number_format($summary['total_records'] ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-indigo-50 p-4 rounded-xl shadow-inner border border-indigo-200">
                            <p class="text-sm font-medium text-indigo-800">Jumlah Diagnosa Unik</p>
                            <p class="text-2xl font-bold text-indigo-900">{{ number_format($summary['unique_diagnosa'] ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @elseif($reportType == 'obat')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 p-4 rounded-xl shadow-inner border border-gray-200">
                            <p class="text-sm font-medium text-gray-800">Total Records Resep</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($summary['total_prescriptions'] ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-orange-50 p-4 rounded-xl shadow-inner border border-orange-200">
                            <p class="text-sm font-medium text-orange-800">Total Jenis Obat Tersedia</p>
                            <p class="text-2xl font-bold text-orange-900">{{ number_format($summary['total_obat_unik'] ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- 2. TABEL DETAIL LAPORAN (Semua ada di sini) -->
            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        {{-- HEADER TABEL --}}
                        @if($reportType == 'kunjungan')
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Tanggal
                                </th>
                                @foreach($clinics as $clinic)
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    {{ $clinic->nama_poli }}
                                </th>
                                @endforeach
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Total Harian
                                </th>
                            </tr>
                        @elseif($reportType == 'diagnosa')
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-12">
                                    #
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Diagnosa (Teks/ICD)
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Jumlah Kasus
                                </th>
                            </tr>
                        @elseif($reportType == 'obat')
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-12">
                                    #
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Nama Obat
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Total Digunakan (Unit)
                                </th>
                            </tr>
                        @endif
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($reportData as $row)
                            {{-- BARIS DATA --}}
                            @if($reportType == 'kunjungan')
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                        {{ \Carbon\Carbon::parse($row['date'])->isoFormat('D MMMM YYYY') }}
                                    </td>
                                    @foreach($clinics as $clinic)
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-700">
                                        {{ $row['clinic_'.$clinic->id] }}
                                    </td>
                                    @endforeach
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-primary">
                                        {{ $row['total'] }}
                                    </td>
                                </tr>
                            @elseif($reportType == 'diagnosa')
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-primary">
                                        {{ $row['rank'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-normal text-sm font-semibold text-gray-900">
                                        {{ $row['diagnosa'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-lg font-bold text-red-600">
                                        {{ number_format($row['total'], 0, ',', '.') }}
                                    </td>
                                </tr>
                            @elseif($reportType == 'obat')
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-primary">
                                        {{ $row['rank'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-normal text-sm font-semibold text-gray-900">
                                        {{ $row['obat'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-lg font-bold text-orange-600">
                                        {{ $row['total_used'] }}
                                    </td>
                                </tr>
                            @endif
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                Silakan pilih periode dan jenis laporan untuk menampilkan data.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
        </div>

    </div>

    {{-- Script JavaScript untuk toggle filter spesifik --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const reportTypeSelect = document.getElementById('report_type');
            const specificFilterContainer = document.getElementById('filter_specific_container');

            // Fungsi untuk menampilkan/menyembunyikan filter spesifik
            function toggleSpecificFilter(reportType) {
                if (reportType === 'kunjungan') {
                    specificFilterContainer.style.display = 'block';
                } else {
                    specificFilterContainer.style.display = 'none';
                }
            }

            // Panggil fungsi saat DOM dimuat (untuk kondisi awal)
            toggleSpecificFilter(reportTypeSelect.value);

            // Kita tambahkan listener untuk memastikan form ter-submit saat report_type berubah
            reportTypeSelect.addEventListener('change', function() {
                this.form.submit();
            });
        });
    </script>

@endsection