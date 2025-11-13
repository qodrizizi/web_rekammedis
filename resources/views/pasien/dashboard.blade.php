@extends('layouts.app')

@section('title', 'Dashboard Saya')

@section('content')

@php
    // Variabel $patient, $summary, $latestAppointment di compact dari PatientController@dashboard

    // Ambil User yang login untuk nama
    $user = Auth::user(); 
    
    // Alergi dan Riwayat Kunjungan akan disimulasikan karena tidak ada data spesifik dari controller
    // Anda perlu mengimplementasikan query untuk Alergi (jika ada tabel terpisah) dan Resep di controller
    $alergiTercatat = false; // Ganti dengan logic DB
    $resepBelumDiambil = 0; // Ganti dengan logic DB
    $totalKunjunganTahunIni = $summary['total_medical_records'] ?? 0;
    
    // Data Dummy Riwayat Kunjungan (untuk contoh tampilan)
    $riwayatDummy = [
        ['tanggal' => '10 November 2025', 'poli' => 'Poli Penyakit Dalam', 'dokter' => 'Dr. Rian Setiawan', 'diagnosa' => 'Demam Tifoid', 'link' => '#'],
        ['tanggal' => '15 Mei 2024', 'poli' => 'Poli Umum', 'dokter' => 'Dr. Amelia', 'diagnosa' => 'Common Cold', 'link' => '#'],
    ];

@endphp

<div class="space-y-8 max-w-6xl mx-auto">
    
    <!-- Header dan Ucapan Selamat Datang -->
    <header class="bg-white p-6 rounded-2xl shadow-xl border-l-8 border-indigo-500 transition duration-300 transform hover:scale-[1.005] hover:shadow-2xl">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-800 flex items-center">
            <i class="bi bi-person-circle text-indigo-600 mr-3"></i> Selamat Datang, <span class="text-indigo-600">{{ $user->name ?? 'Pasien' }}</span>
        </h1>
        <p class="text-base sm:text-lg text-gray-600 mt-2">
            {{-- SAFE ACCESS: Tambahkan ? sebelum mengakses properti object --}}
            ID Pasien: <strong class="text-indigo-600">{{ $patient?->id ?? 'N/A' }}</strong>. Pantau riwayat kesehatan dan janji temu Anda di sini.
        </p>
    </header>

    <!-- 1. Ringkasan Status Kesehatan dan Janji Temu (Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        <!-- Card 1: Janji Temu Mendatang (Mengambil dari $summary dan $latestAppointment) -->
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

        <!-- Card 2: Alergi Obat (Simulasi) -->
        <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 flex items-center justify-between border border-gray-100">
            <div>
                <p class="text-sm font-medium text-gray-500">Alergi Obat Tercatat</p>
                <p class="text-4xl font-bold {{ $alergiTercatat ? 'text-red-600' : 'text-gray-900' }} mt-1">{{ $alergiTercatat ? 'YA' : 'TIDAK' }}</p>
                <p class="text-xs {{ $alergiTercatat ? 'text-red-500' : 'text-gray-500' }} font-medium mt-1">
                    {{ $alergiTercatat ? 'Amoxicillin (Simulasi)' : 'Data alergi bersih' }}
                </p>
            </div>
            <div class="bg-red-500/10 p-3 rounded-xl text-red-600 flex items-center justify-center">
                <i class="bi bi-exclamation-octagon-fill text-3xl"></i>
            </div>
        </div>

        <!-- Card 3: Resep Belum Diambil (Mengambil dari $resepBelumDiambil, perlu logic di Controller) -->
        <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 flex items-center justify-between border border-gray-100">
            <div>
                <p class="text-sm font-medium text-gray-500">Resep Belum Diambil</p>
                <p class="text-4xl font-bold text-orange-600 mt-1">{{ $resepBelumDiambil }}</p>
                <p class="text-xs text-gray-500 font-medium mt-1">
                    {{ $resepBelumDiambil == 0 ? 'Semua resep sudah diambil' : $resepBelumDiambil . ' resep menanti' }}
                </p>
            </div>
            <div class="bg-orange-500/10 p-3 rounded-xl text-orange-600 flex items-center justify-center">
                <i class="bi bi-box-seam-fill text-3xl"></i>
            </div>
        </div>

        <!-- Card 4: Total Kunjungan (Mengambil dari $summary) -->
        <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 flex items-center justify-between border border-gray-100">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Kunjungan (RM)</p>
                <p class="text-4xl font-bold text-gray-900 mt-1">{{ $totalKunjunganTahunIni }}</p>
                <p class="text-xs text-gray-500 font-medium mt-1">
                    {{ $totalKunjunganTahunIni > 0 ? 'Total ' . $totalKunjunganTahunIni . ' riwayat tercatat' : 'Belum ada riwayat' }}
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

            <!-- Riwayat Kunjungan Terbaru (Simulasi) -->
            <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="bi bi-journal-medical text-indigo-600 mr-2"></i> Kunjungan Rekam Medis Terbaru
                </h2>
                
                @if ($totalKunjunganTahunIni > 0)
                <div class="divide-y divide-gray-200">
                    
                    @foreach ($riwayatDummy as $riwayat)
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-3 hover:bg-gray-50 rounded-lg -mx-2 px-2 transition">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $riwayat['tanggal'] }}</p>
                            <p class="text-sm text-gray-500">{{ $riwayat['poli'] }} - {{ $riwayat['dokter'] }}</p>
                        </div>
                        <span class="mt-2 sm:mt-0 text-xs bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full font-medium min-w-[120px] text-center">Diagnosis: {{ $riwayat['diagnosa'] }}</span>
                        <a href="{{ $riwayat['link'] }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium mt-2 sm:mt-0">Lihat Detail RM &rarr;</a>
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
                
                <ul class="space-y-3 text-sm text-gray-700">
                    <li class="border-b border-gray-100 pb-2">
                        <span class="font-semibold block">Tanggal Lahir:</span> 
                        {{-- PERBAIKAN DI SINI (Baris 167) --}}
                        @if($patient?->tanggal_lahir)
                            {{ \Carbon\Carbon::parse($patient->tanggal_lahir)->format('d F Y') }} 
                            ({{ \Carbon\Carbon::parse($patient->tanggal_lahir)->age }} Tahun)
                        @else
                            N/A
                        @endif
                    </li>
                    <li class="border-b border-gray-100 pb-2">
                        <span class="font-semibold block">Jenis Kelamin:</span> 
                        {{ $patient?->jenis_kelamin == 'L' ? 'Laki-laki' : ($patient?->jenis_kelamin == 'P' ? 'Perempuan' : 'N/A') }}
                    </li>
                    <li class="border-b border-gray-100 pb-2">
                        <span class="font-semibold block">No. BPJS:</span> 
                        {{ $patient?->no_bpjs ?? 'Tidak Terdaftar' }}
                    </li>
                    <li class="border-b border-gray-100 pb-2">
                        <span class="font-semibold block">No. HP:</span> 
                        {{ $patient?->no_hp ?? 'N/A' }}
                    </li>
                    <li>
                        <span class="font-semibold block">Alamat:</span> 
                        {{ $patient?->alamat ?? 'N/A' }}
                    </li>
                </ul>

                <div class="mt-4 text-center">
                    <a href="{{ route('pasien.profil') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium transition duration-150">Ubah Data Profil &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection