@extends('layouts.app')

@section('title', 'Dashboard Saya')

@section('content')

@php
    // Variabel yang dikirim dari Controller: $user, $patient, $summary, $latestAppointment, $alergiTercatat, $latestRecords
    $totalKunjungan = $summary['total_medical_records'] ?? 0;
@endphp

<div class="space-y-8 max-w-6xl mx-auto">
    
    <!-- Header dan Ucapan Selamat Datang -->
    <header class="bg-white p-6 rounded-2xl shadow-xl border-l-8 border-indigo-500 transition duration-300 transform hover:scale-[1.005] hover:shadow-2xl">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-800 flex items-center">
            <i class="bi bi-person-circle text-indigo-600 mr-3"></i> Selamat Datang, <span class="text-indigo-600">{{ $user->name ?? 'Pasien' }}</span>
        </h1>
        <p class="text-base sm:text-lg text-gray-600 mt-2">
            ID Pasien: <strong class="text-indigo-600">{{ $patient?->id ?? 'N/A' }}</strong>. Pantau riwayat kesehatan dan janji temu Anda di sini.
        </p>
    </header>

    <!-- 1. Ringkasan Status Kesehatan dan Janji Temu (Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        <!-- Card 1: Janji Temu Aktif -->
        <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 flex items-center justify-between border border-gray-100">
            <div>
                <p class="text-sm font-medium text-gray-500">Janji Temu Aktif</p>
                <p class="text-4xl font-bold text-gray-900 mt-1">{{ $summary['active_appointments'] ?? 0 }}</p>
                <p class="text-xs text-indigo-500 font-medium mt-1">
                    @if($latestAppointment)
                        {{ \Carbon\Carbon::parse($latestAppointment->tanggal_kunjungan)->format('d M Y') }}, {{ \Carbon\Carbon::parse($latestAppointment->jam_kunjungan)->format('H:i') }}
                    @else
                        Tidak ada janji
                    @endif
                </p>
            </div>
            <div class="bg-indigo-500/10 p-3 rounded-xl text-indigo-600 flex items-center justify-center">
                <i class="bi bi-calendar-check-fill text-3xl"></i>
            </div>
        </div>

        <!-- Card 2: Alergi Obat (Diambil dari $alergiTercatat dan $patient) -->
        <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 flex items-center justify-between border border-gray-100">
            <div>
                <p class="text-sm font-medium text-gray-500">Alergi Obat Tercatat</p>
                <p class="text-4xl font-bold {{ $alergiTercatat ? 'text-red-600' : 'text-gray-900' }} mt-1">
                    {{ $alergiTercatat ? 'YA' : 'TIDAK' }}
                </p>
                <p class="text-xs {{ $alergiTercatat ? 'text-red-500' : 'text-gray-500' }} font-medium mt-1">
                    @if($alergiTercatat)
                        {{ Str::limit($patient->riwayat_alergi, 30) }}
                    @else
                        Data alergi bersih
                    @endif
                </p>
            </div>
            <div class="bg-red-500/10 p-3 rounded-xl text-red-600 flex items-center justify-center">
                <i class="bi bi-exclamation-octagon-fill text-3xl"></i>
            </div>
        </div>

        <!-- Card 3: Resep Belum Diambil (Masih Placeholder, perlu tabel prescriptions) -->
        <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 flex items-center justify-between border border-gray-100">
            <div>
                <p class="text-sm font-medium text-gray-500">Resep Belum Diambil</p>
                <p class="text-4xl font-bold text-orange-600 mt-1">0</p>
                <p class="text-xs text-gray-500 font-medium mt-1">
                    Semua resep sudah diambil
                </p>
            </div>
            <div class="bg-orange-500/10 p-3 rounded-xl text-orange-600 flex items-center justify-center">
                <i class="bi bi-box-seam-fill text-3xl"></i>
            </div>
        </div>

        <!-- Card 4: Total Kunjungan (RM) -->
        <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 flex items-center justify-between border border-gray-100">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Kunjungan (RM)</p>
                <p class="text-4xl font-bold text-gray-900 mt-1">{{ $totalKunjungan }}</p>
                <p class="text-xs text-gray-500 font-medium mt-1">
                    {{ $totalKunjungan > 0 ? 'Total ' . $totalKunjungan . ' riwayat tercatat' : 'Belum ada riwayat' }}
                </p>
            </div>
            <div class="bg-green-500/10 p-3 rounded-xl text-green-600 flex items-center justify-center">
                <i class="bi bi-activity text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- 2. Main Content: Aksi Cepat & Riwayat Terbaru -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Kolom Kiri (2/3 lebar): Riwayat RM & Jadwal -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Aksi Cepat -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a href="{{ route('pasien.konsultasi') }}" class="flex items-center justify-between p-5 bg-indigo-500 hover:bg-indigo-600 text-white rounded-2xl shadow-md transition duration-300">
                    <div>
                        <p class="font-bold text-xl">Buat Janji Temu</p>
                        <p class="text-sm opacity-90">Jadwalkan konsultasi berikutnya.</p>
                    </div>
                    <i class="bi bi-calendar-plus-fill text-3xl"></i>
                </a>
                <a href="#" class="flex items-center justify-between p-5 bg-yellow-500 hover:bg-yellow-600 text-white rounded-2xl shadow-md transition duration-300">
                    <div>
                        <p class="font-bold text-xl">Lihat Antrian Poli</p>
                        <p class="text-sm opacity-90">Cek status antrian kunjungan.</p>
                    </div>
                    <i class="bi bi-list-ol text-3xl"></i>
                </a>
            </div>

            <!-- Riwayat Kunjungan Terbaru (Dari $latestRecords) -->
            <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="bi bi-journal-medical text-indigo-600 mr-2"></i> Kunjungan Rekam Medis Terbaru
                </h2>
                
                @if ($totalKunjungan > 0 && $latestRecords->isNotEmpty())
                <div class="divide-y divide-gray-200">
                    
                    @foreach ($latestRecords as $record)
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-3 hover:bg-gray-50 rounded-lg -mx-2 px-2 transition">
                        <div>
                            <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($record->tanggal_periksa)->format('d F Y') }}</p>
                            <p class="text-sm text-gray-500">{{ $record->clinic->nama_poli ?? 'Poli N/A' }} - Dr. {{ $record->doctor->user->name ?? 'N/A' }}</p>
                        </div>
                        <span class="mt-2 sm:mt-0 text-xs bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full font-medium min-w-[120px] text-center">Diagnosis: {{ Str::limit($record->diagnosa ?? 'N/A', 25) }}</span>
                        <a href="{{ route('pasien.rekam_medis') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium mt-2 sm:mt-0">Lihat Detail RM &rarr;</a>
                    </div>
                    @endforeach
                    
                </div>
                <div class="mt-4 text-center">
                    <a href="{{ route('pasien.rekam_medis') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium transition duration-150">Lihat Semua Riwayat Kunjungan &rarr;</a>
                </div>
                @else
                <p class="text-gray-500 italic">Belum ada riwayat kunjungan medis yang tercatat.</p>
                @endif
            </div>
        </div>
        
        <!-- Kolom Kanan (1/3 lebar): Informasi Profil & Kontak -->
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100 sticky lg:top-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="bi bi-file-person-fill text-indigo-600 mr-2"></i> Informasi Profil
                </h2>
                
                @if($patient)
                <ul class="space-y-3 text-sm text-gray-700">
                    <li class="border-b border-gray-100 pb-2">
                        <span class="font-semibold block">Tanggal Lahir:</span> 
                        @if($patient->tanggal_lahir)
                            {{ \Carbon\Carbon::parse($patient->tanggal_lahir)->format('d F Y') }} 
                            ({{ \Carbon\Carbon::parse($patient->tanggal_lahir)->age }} Tahun)
                        @else
                            <span class="italic text-red-500">Wajib Diisi</span>
                        @endif
                    </li>
                    <li class="border-b border-gray-100 pb-2">
                        <span class="font-semibold block">Jenis Kelamin:</span> 
                        {{ $patient->jenis_kelamin == 'L' ? 'Laki-laki' : ($patient->jenis_kelamin == 'P' ? 'Perempuan' : 'N/A') }}
                    </li>
                    <li class="border-b border-gray-100 pb-2">
                        <span class="font-semibold block">Golongan Darah:</span> 
                        {{ $patient->gol_darah ?? 'N/A' }}
                    </li>
                    <li class="border-b border-gray-100 pb-2">
                        <span class="font-semibold block">No. BPJS:</span> 
                        {{ $patient->no_bpjs ?? 'Tidak Terdaftar' }}
                    </li>
                    <li>
                        <span class="font-semibold block">Alamat:</span> 
                        {{ $patient->alamat ?? 'N/A' }}
                    </li>
                </ul>
                @else
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 rounded-md text-sm text-yellow-800">
                    <p class="font-semibold">Data Profil Belum Lengkap.</p>
                    <p>Mohon segera lengkapi NIK dan Tanggal Lahir di halaman Profil.</p>
                </div>
                @endif

                <div class="mt-4 text-center">
                    <a href="{{ route('pasien.profil') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium transition duration-150">Ubah Data Profil &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection