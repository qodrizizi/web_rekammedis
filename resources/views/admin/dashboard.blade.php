{{-- resources/views/admin/dashboard.blade.php --}}

@extends('layouts.app')

@section('title', 'Dashboard Administrator')

@section('content')

<div class="space-y-8">
    
    <!-- Header dan Ucapan Selamat Datang -->
    <header class="bg-white p-6 rounded-2xl shadow-lg border-l-8 border-primary transition duration-300 transform hover:scale-[1.005]">
        <h1 class="text-4xl font-extrabold text-gray-800 flex items-center">
            <i class="bi bi-shield-lock text-primary mr-3"></i> Dashboard Administrator
        </h1>
        <p class="text-lg text-gray-600 mt-2">
            Selamat datang kembali di sistem rekam medis digital, **{{ Auth::user()->name }}**! 
            Anda memiliki ringkasan data penting hari ini.
        </p>
    </header>

    <!-- 1. Ringkasan Kartu Data (Stats Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        <!-- Card 1: Total Pasien -->
        <div class="bg-white p-6 rounded-2xl shadow-xl hover:shadow-2xl transition duration-300 flex items-center justify-between border border-gray-100">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Pasien Terdaftar</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['total_pasien'] }}</p>
            </div>
            <div class="bg-primary/10 p-3 rounded-full text-primary">
                <i class="bi bi-people-fill text-2xl"></i>
            </div>
        </div>

        <!-- Card 2: Janji Hari Ini -->
        <div class="bg-white p-6 rounded-2xl shadow-xl hover:shadow-2xl transition duration-300 flex items-center justify-between border border-gray-100">
            <div>
                <p class="text-sm font-medium text-gray-500">Janji Kunjungan Hari Ini</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['janji_hari_ini']) }}</p>
            </div>
            <div class="bg-green-500/10 p-3 rounded-full text-green-600">
                <i class="bi bi-calendar-check text-2xl"></i>
            </div>
        </div>

        <!-- Card 3: Dokter Aktif -->
        <div class="bg-white p-6 rounded-2xl shadow-xl hover:shadow-2xl transition duration-300 flex items-center justify-between border border-gray-100">
            <div>
                <p class="text-sm font-medium text-gray-500">Dokter Aktif</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['dokter_aktif']) }}</p>
            </div>
            <div class="bg-yellow-500/10 p-3 rounded-full text-yellow-600">
                <i class="bi bi-person-badge-fill text-2xl"></i>
            </div>
        </div>

        <!-- Card 4: Stok Obat Kritis -->
        <div class="bg-white p-6 rounded-2xl shadow-xl hover:shadow-2xl transition duration-300 flex items-center justify-between border border-gray-100">
            <div>
                <p class="text-sm font-medium text-gray-500">Stok Obat Kritis</p>
                <p class="text-3xl font-bold text-red-600 mt-1">{{ number_format($stats['stok_kritis']) }}</p>
            </div>
            <div class="bg-red-500/10 p-3 rounded-full text-red-600">
                <i class="bi bi-capsule-pill text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- 2. Main Content: Janji Terbaru & Log Aktivitas -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Kolom Kiri (2/3 lebar di desktop) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Grafik Penjualan atau Kunjungan -->
            <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Tren Kunjungan 6 Bulan Terakhir</h2>
                <div class="h-64">
                    <canvas id="visitChart"></canvas>
                </div>
            </div>

            <!-- Daftar Janji Kunjungan Terbaru -->
            <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Janji Kunjungan Mendatang</h2>
                <div class="divide-y divide-gray-200">
                    @forelse($upcomingAppointments as $appointment)
                        <div class="flex justify-between items-center py-3">
                            <div>
                                <p class="font-semibold text-gray-900">Pasien: {{ $appointment->patient->user->name ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ $appointment->clinic->nama_poli ?? 'N/A' }} - {{ $appointment->doctor->user->name ?? 'N/A' }}
                                </p>
                            </div>
                            <span class="text-xs bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-medium whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($appointment->tanggal_kunjungan)->isoFormat('D MMM YYYY') }}, 
                                {{ $appointment->jam_kunjungan ? \Carbon\Carbon::parse($appointment->jam_kunjungan)->format('H:i') : 'Waktu Belum Diset' }}
                            </span>
                        </div>
                    @empty
                        <div class="py-4 text-center text-gray-500">Tidak ada janji kunjungan yang dijadwalkan saat ini.</div>
                    @endforelse
                </div>
                <div class="mt-4 text-center">
                    <a href="{{ route('admin.pendaftaran') }}" class="text-primary hover:text-secondary text-sm font-medium">Lihat Semua Janji Kunjungan &rarr;</a>
                </div>
            </div>
        </div>
        
        <!-- Kolom Kanan (1/3 lebar di desktop) -->
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="bi bi-activity text-lg mr-2 text-gray-500"></i> Log Aktivitas Terbaru
                </h2>
                
                <ul class="space-y-4">
                    @forelse($recentActivityLogs as $log)
                        @php
                            $logColor = ['Tambah' => 'bg-green-500', 'Ubah' => 'bg-yellow-500', 'Hapus' => 'bg-red-500', 'Sistem' => 'bg-primary'][$log->aksi] ?? 'bg-gray-500';
                            $logUser = $log->user->name ?? 'Sistem/Pengguna Dihapus';
                            // Logic untuk menampilkan waktu relatif (misal: 5 menit yang lalu)
                            $timeAgo = \Carbon\Carbon::parse($log->waktu)->diffForHumans(); 
                        @endphp
                        <li class="flex items-start space-x-3">
                            <div class="w-2 h-2 {{ $logColor }} rounded-full mt-2 flex-shrink-0"></div>
                            <div>
                                <p class="text-sm text-gray-800 line-clamp-2">
                                    <span class="font-semibold">{{ $logUser }}</span> {{ $log->deskripsi }}
                                </p>
                                <p class="text-xs text-gray-500">{{ $timeAgo }}</p>
                            </div>
                        </li>
                    @empty
                        <li class="text-center text-gray-500">Belum ada log aktivitas terbaru.</li>
                    @endforelse
                </ul>
                <div class="mt-4 text-center">
                    <a href="#" class="text-primary hover:text-secondary text-sm font-medium">Lihat Semua Log &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Import Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('visitChart').getContext('2d');
        
        // Data dari Controller Laravel
        const monthlyVisitsData = @json($monthlyVisits);
        
        const data = {
            labels: monthlyVisitsData.labels,
            datasets: [{
                label: 'Jumlah Kunjungan Selesai',
                data: monthlyVisitsData.data,
                backgroundColor: 'rgba(59, 130, 246, 0.5)', // Tailwind blue-500
                borderColor: 'rgba(37, 99, 235, 1)', // Tailwind blue-600
                borderWidth: 1,
                fill: true,
                tension: 0.3
            }]
        };

        const config = {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                if (value % 1 === 0) {
                                    return value;
                                }
                            }
                        }
                    }
                }
            }
        };

        new Chart(ctx, config);
    });
</script>

@endsection