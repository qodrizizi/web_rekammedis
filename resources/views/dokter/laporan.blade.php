@extends('layouts.app')

@section('title', 'Laporan Kinerja Klinis Saya')

@section('content')

    <div class="space-y-6">
        
        {{-- HEADER --}}
        <header class="bg-white p-6 rounded-2xl shadow-xl border-l-8 border-primary border-t border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center mb-1">
                    <i class="bi bi-bar-chart-line-fill text-primary mr-3"></i> Laporan Kinerja Klinis
                </h1>
                <p class="text-lg text-gray-600">Ringkasan aktivitas dan performa medis Anda.</p>
            </div>
            
            {{-- Filter Periode (Visual Saja) --}}
            <div class="flex items-center space-x-3">
                <div class="flex items-center bg-gray-50 rounded-xl px-3 py-2 border border-gray-200">
                    <i class="bi bi-calendar-check text-gray-500 mr-2"></i>
                    <span class="text-sm font-medium text-gray-700">
                        {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
                    </span>
                </div>
                <a href="{{ route('dokter.laporan.export') }}" class="bg-primary hover:bg-secondary text-white font-semibold py-2 px-4 rounded-xl transition-colors duration-300 flex items-center shadow-md">
                    <i class="bi bi-file-earmark-pdf mr-2"></i> Export PDF
                </a>
            </div>
        </header>

        {{-- KARTU STATISTIK --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            {{-- Kartu 1: Total Pasien Diperiksa --}}
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:border-primary/30 transition duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Total Pemeriksaan</p>
                        <h3 class="text-3xl font-bold text-gray-800">{{ $stats['total_checked'] }}</h3>
                    </div>
                    <div class="bg-blue-50 p-3 rounded-xl text-blue-600">
                        <i class="bi bi-people-fill text-2xl"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-4">Total kunjungan bulan ini</p>
            </div>

            {{-- Kartu 2: Pasien Unik (Logic Baru) --}}
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:border-green-500/30 transition duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Pasien Unik</p>
                        <h3 class="text-3xl font-bold text-gray-800">{{ $stats['new_records'] }}</h3>
                    </div>
                    <div class="bg-green-50 p-3 rounded-xl text-green-600">
                        <i class="bi bi-person-check-fill text-2xl"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-4">Orang berbeda yang ditangani</p>
            </div>

            {{-- Kartu 3: Diagnosis Unik --}}
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:border-yellow-500/30 transition duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Variasi Diagnosis</p>
                        <h3 class="text-3xl font-bold text-gray-800">{{ $stats['unique_diagnoses'] }}</h3>
                    </div>
                    <div class="bg-yellow-50 p-3 rounded-xl text-yellow-600">
                        <i class="bi bi-activity text-2xl"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-4">Jenis penyakit berbeda</p>
            </div>

            {{-- Kartu 4: Rata-rata Waktu --}}
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100 hover:border-red-500/30 transition duration-300">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Est. Waktu/Pasien</p>
                        <h3 class="text-3xl font-bold text-gray-800">{{ $stats['avg_time'] }} <span class="text-base font-normal text-gray-500">mnt</span></h3>
                    </div>
                    <div class="bg-red-50 p-3 rounded-xl text-red-600">
                        <i class="bi bi-stopwatch-fill text-2xl"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-4">Rata-rata durasi konsultasi</p>
            </div>
        </div>

        {{-- SECTION GRAFIK & LIST DIAGNOSIS --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- CHART AREA --}}
            <div class="lg:col-span-2">
                <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100 h-full">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                        <i class="bi bi-graph-up-arrow text-primary mr-2"></i> Tren Kunjungan Pasien (6 Bulan)
                    </h2>
                    
                    <div class="relative h-72 w-full">
                        <canvas id="visitTrendChart"></canvas>
                    </div>
                </div>
            </div>
            
            {{-- TOP DIAGNOSIS LIST --}}
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100 h-full">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                        <i class="bi bi-clipboard2-pulse-fill text-primary mr-2"></i> 5 Diagnosis Terbanyak
                    </h2>
                    
                    <div class="space-y-4">
                        @forelse ($stats['top_diagnoses'] as $index => $diagnosis)
                            <div class="group flex items-center justify-between p-3 rounded-xl bg-gray-50 hover:bg-blue-50 transition duration-200 border border-transparent hover:border-blue-100">
                                <div class="flex items-center overflow-hidden">
                                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm mr-3 group-hover:bg-blue-600 group-hover:text-white transition">
                                        {{ $index + 1 }}
                                    </div>
                                    <span class="font-medium text-gray-700 truncate group-hover:text-blue-700 transition" title="{{ $diagnosis->diagnosa }}">
                                        {{ $diagnosis->diagnosa }}
                                    </span>
                                </div>
                                <span class="flex-shrink-0 bg-white px-3 py-1 rounded-full text-xs font-bold text-gray-600 shadow-sm border border-gray-100">
                                    {{ $diagnosis->total }} Kasus
                                </span>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <div class="bg-gray-100 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-3 text-gray-400">
                                    <i class="bi bi-inbox-fill text-xl"></i>
                                </div>
                                <p class="text-gray-500 text-sm">Belum ada data diagnosis bulan ini.</p>
                            </div>
                        @endforelse
                    </div>
                    
                    @if(count($stats['top_diagnoses']) > 0)
                    <div class="mt-6 pt-4 border-t border-gray-100 text-center">
                        <p class="text-xs text-gray-400">Berdasarkan rekam medis bulan ini</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    {{-- SCRIPT UNTUK CHART.JS --}}
    {{-- Pastikan layout utama Anda sudah memuat Chart.js, jika belum, uncomment baris di bawah --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Persiapkan Data dari Controller
            const trendData = @json($monthlyTrend);
            
            const labels = trendData.map(item => item.month);
            const dataValues = trendData.map(item => item.count);

            const ctx = document.getElementById('visitTrendChart').getContext('2d');
            
            // Buat Gradient untuk background chart agar lebih cantik
            let gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(59, 130, 246, 0.5)'); // Warna Primary (Blue) transparan
            gradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

            new Chart(ctx, {
                type: 'line', // Bisa diganti 'bar' jika suka diagram batang
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Pasien',
                        data: dataValues,
                        backgroundColor: gradient,
                        borderColor: '#3B82F6', // Warna garis (Blue-500)
                        borderWidth: 2,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#3B82F6',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.3 // Membuat garis sedikit melengkung (smooth)
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false // Sembunyikan legend karena cuma 1 data
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(255, 255, 255, 0.9)',
                            titleColor: '#1F2937',
                            bodyColor: '#1F2937',
                            borderColor: '#E5E7EB',
                            borderWidth: 1,
                            padding: 10,
                            displayColors: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                borderDash: [2, 2],
                                color: '#F3F4F6'
                            },
                            ticks: {
                                stepSize: 1 // Pastikan angka sumbu Y bulat (tidak ada 1.5 orang)
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    </script>

@endsection