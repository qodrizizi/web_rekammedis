@extends('layouts.app')

{{-- Mengganti judul halaman --}}
@section('title', 'Dashboard Dokter - Rekam Medis Digital')

@section('content')

{{-- Konten Dashboard Dokter Anda dimulai di sini --}}
<div class="space-y-8 max-w-7xl mx-auto p-4 sm:p-0">
    
    {{-- Inline SVG untuk Ikon (Disederhanakan menggunakan bi-class, tetapi jika perlu SVG, ini kodenya) --}}
    <svg style="display: none;">
        <symbol id="icon-stethoscope" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10a10 10 0 1 0-20 0h2"/><path d="M10 12v6"/><path d="M14 12v6"/><path d="M12 18v4"/><path d="M12 2h2"/><path d="M12 2v2"/></symbol>
        <symbol id="icon-calendar-clock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 7.5V18a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h8.5"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/><circle cx="17" cy="15" r="3"/><path d="M17 14v1l1 1"/></symbol>
        <symbol id="icon-user-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="m19 19 2 2"/><path d="m22 17-5 5"/></symbol>
        <symbol id="icon-file-medical" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><path d="M14 2v5h5"/><path d="M12 11v6"/><path d="M9 14h6"/></symbol>
        <symbol id="icon-pills" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m20.6 15.3-.9-1.2"/><path d="m16 9-1.2-.9"/><path d="m11.2 5.3-1.4 1.4"/><path d="m5.3 11.2 1.4 1.4"/><path d="M10.4 20.6a2.13 2.13 0 0 1-2.9-2.9l12.4-12.4a2.13 2.13 0 0 1 2.9 2.9Z"/><path d="m14.8 7.6 1.4 1.4"/></symbol>
        <symbol id="icon-bell-ring" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.36 17.53a2 2 0 0 0 3.28 0"/><path d="M12 2v2"/></symbol>
    </svg>

    <header class="bg-white p-6 rounded-2xl shadow-xl border-l-8 border-primary transition duration-300 transform hover:scale-[1.005] hover:shadow-2xl">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-800 flex items-center">
            <i class="bi bi-person-badge-fill text-primary mr-3 text-4xl"></i> Dashboard Dokter
        </h1>
        <p class="text-base sm:text-lg text-gray-600 mt-2">
            Selamat pagi, <strong class="text-primary">{{ $doctorName }}</strong>! 
            Berikut ringkasan jadwal dan tugas Anda hari ini, **{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}**.
        </p>
    </header>

    {{-- KARTU STATISTIK --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 flex items-center justify-between border border-gray-100">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Pasien Hari Ini</p>
                <p class="text-4xl font-bold text-gray-900 mt-1">{{ $stats['total_today'] }}</p>
            </div>
            <div class="bg-primary/10 p-3 rounded-xl text-primary flex items-center justify-center">
                <i class="bi bi-calendar-check text-3xl"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 flex items-center justify-between border border-gray-100">
            <div>
                <p class="text-sm font-medium text-gray-500">Selesai Diperiksa</p>
                <p class="text-4xl font-bold text-gray-900 mt-1">{{ $stats['completed_today'] }}</p>
            </div>
            <div class="bg-blue-500/10 p-3 rounded-xl text-blue-600 flex items-center justify-center">
                <i class="bi bi-person-check-fill text-3xl"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 flex items-center justify-between border border-gray-100">
            <div>
                <p class="text-sm font-medium text-gray-500">Rekam Medis Bulan Ini</p>
                <p class="text-4xl font-bold text-gray-900 mt-1">{{ $stats['rm_month'] }}</p>
            </div>
            <div class="bg-yellow-500/10 p-3 rounded-xl text-yellow-600 flex items-center justify-center">
                <i class="bi bi-file-medical-fill text-3xl"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 flex items-center justify-between border border-gray-100">
            <div>
                <p class="text-sm font-medium text-gray-500">Resep Tertunda</p>
                <p class="text-4xl font-bold text-orange-600 mt-1">{{ $stats['pending_resep'] }}</p>
            </div>
            <div class="bg-orange-500/10 p-3 rounded-xl text-orange-600 flex items-center justify-center">
                <i class="bi bi-box-seam-fill text-3xl"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- BAGIAN KIRI: JADWAL KUNJUNGAN & RM TERBARU --}}
        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="bi bi-calendar-check text-primary mr-2"></i> Jadwal Kunjungan Pasien Hari Ini (Top 4)
                </h2>
                
                <div class="divide-y divide-gray-200">
                    @forelse ($todayAppointments as $appointment)
                        @php
                            $namaPasien = $appointment->patient->user->name ?? 'Pasien Tidak Dikenal';
                            $status = $appointment->status;
                            $time = \Carbon\Carbon::parse($appointment->jam_kunjungan)->format('H:i');

                            $badgeClass = [
                                'menunggu' => 'bg-blue-100 text-blue-800',
                                'disetujui' => 'bg-yellow-100 text-yellow-800',
                                'selesai' => 'bg-green-100 text-green-800',
                                'batal' => 'bg-red-100 text-red-800',
                            ][$status] ?? 'bg-gray-100 text-gray-800';

                            $borderClass = $status == 'disetujui' ? 'border-l-4 border-yellow-500 pl-3' : 'pl-3';
                        @endphp
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-3 {{ $borderClass }} hover:bg-gray-50 rounded-lg -mx-2 px-2 transition">
                            <div>
                                <p class="font-semibold text-gray-900">Pasien: {{ $namaPasien }} (ID P{{ $appointment->patient_id }})</p>
                                <p class="text-sm text-gray-500">Keluhan: {{ $appointment->keluhan ?? '-' }}</p>
                            </div>
                            <span class="mt-2 sm:mt-0 text-xs {{ $badgeClass }} px-3 py-1 rounded-full font-medium min-w-[150px] text-center">
                                {{ $time }} - {{ ucfirst($status) }}
                            </span>
                        </div>
                    @empty
                        <div class="py-4 text-center text-gray-500 italic">Tidak ada antrian pasien yang tercatat hari ini.</div>
                    @endforelse
                </div>
                
                <div class="mt-4 text-center">
                    <a href="{{ route('dokter.pasien') }}" class="text-primary hover:text-secondary text-sm font-medium transition duration-150">Lihat Semua Antrian &rarr;</a>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="bi bi-file-text text-primary mr-2"></i> Rekam Medis Terakhir Saya
                </h2>
                <ul class="space-y-3">
                    @forelse ($recentRecords as $record)
                        @php
                            $namaPasien = $record->patient->user->name ?? 'Pasien Tidak Dikenal';
                        @endphp
                        <li class="p-3 bg-gray-50 rounded-lg flex justify-between items-center hover:bg-gray-100 transition">
                            <div>
                                <p class="font-medium text-gray-900">Pasien: {{ $namaPasien }} (ID P{{ $record->patient_id }})</p>
                                <p class="text-sm text-gray-600">Diagnosis: {{ $record->diagnosa ?? 'N/A' }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-xs text-gray-500 block">Diperbarui: {{ \Carbon\Carbon::parse($record->updated_at)->format('d M Y') }}</span>
                                <a href="{{ route('dokter.rekam_medis') }}" class="text-primary hover:text-secondary text-sm font-medium mt-1 inline-block">Lihat Detail</a>
                            </div>
                        </li>
                    @empty
                        <li class="p-3 text-center text-gray-500 italic">Belum ada Rekam Medis yang dicatat oleh Anda.</li>
                    @endforelse
                </ul>
            </div>
        </div>
        
        {{-- BAGIAN KANAN: NOTIFIKASI --}}
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100 sticky top-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="bi bi-bell-fill text-red-500 mr-2"></i> Pengingat & Notifikasi Penting
                </h2>
                
                <ul class="space-y-4">
                    {{-- Notifikasi Resep Tertunda (Dinamis dari stats) --}}
                    @if ($stats['pending_resep'] > 0)
                        <li class="flex items-start space-x-3 p-3 bg-red-50 border-l-4 border-red-500 rounded-lg">
                            <div class="w-2 h-2 bg-red-500 rounded-full mt-2.5 flex-shrink-0"></div>
                            <div>
                                <p class="text-sm text-gray-800 font-semibold">Tinjau Resep Obat</p>
                                <p class="text-sm text-gray-600">{{ $stats['pending_resep'] }} Resep pasien **perlu ditandatangani** segera.</p>
                                <a href="#" class="text-xs text-primary hover:text-secondary">Klik untuk lihat detail resep.</a>
                            </div>
                        </li>
                    @endif
                    
                    {{-- Dummy Notifikasi Lain --}}
                    <li class="flex items-start space-x-3">
                        <div class="w-2 h-2 bg-primary rounded-full mt-2.5 flex-shrink-0"></div>
                        <div>
                            <p class="text-sm text-gray-800"><span class="font-semibold">Jadwal baru:</span> Pasien Rina Dewi ditambahkan ke antrian 14:15.</p>
                            <p class="text-xs text-gray-500">5 menit yang lalu</p>
                        </div>
                    </li>
                    <li class="flex items-start space-x-3">
                        <div class="w-2 h-2 bg-blue-500 rounded-full mt-2.5 flex-shrink-0"></div>
                        <div>
                            <p class="text-sm text-gray-800"><span class="font-semibold">Pesan:</span> Perawat Siti meminta persetujuan untuk tindakan darurat.</p>
                            <p class="text-xs text-gray-500">15 menit yang lalu</p>
                        </div>
                    </li>
                </ul>
                <div class="mt-4 text-center">
                    <a href="#" class="text-primary hover:text-secondary text-sm font-medium transition duration-150">Lihat Semua Notifikasi &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection