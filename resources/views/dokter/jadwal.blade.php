@extends('layouts.app')

@section('title', 'Jadwal Praktek Saya')

@section('content')

    <div class="space-y-6">
        
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <i class="bi bi-calendar-week-fill text-primary mr-3"></i> Jadwal Praktek Saya
            </h1>
        </div>

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @if (isset($doctor))
            
            {{-- Info Profil Dokter --}}
            <div class="bg-white shadow-xl rounded-2xl p-6 border-l-4 border-blue-600">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="bi bi-person-circle text-blue-600 mr-2"></i> Detail Profesional
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700">
                    <div>
                        <p class="text-sm font-semibold text-gray-500">Nama Dokter</p>
                        <p class="text-lg font-bold">{{ $doctor->user->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-500">Nomor Lisensi (STR)</p>
                        <p class="text-lg font-bold">{{ $doctor->str_number ?? 'Belum terdaftar' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-500">Spesialisasi</p>
                        <p class="text-lg font-bold">{{ $doctor->specialization ?? 'Umum' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-500">Poliklinik Tugas</p>
                        <p class="text-lg font-bold">
                            {{ $clinic->nama_poliklinik ?? 'N/A (Cek Relasi)' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Detail Jadwal Praktek --}}
            <div class="bg-gradient-to-r from-teal-50 to-cyan-50 shadow-xl rounded-2xl p-6 border-2 border-teal-200">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="bi bi-clock-fill text-teal-600 mr-2"></i> Jadwal Pelayanan Mingguan
                </h2>
                
                {{-- ASUMSI: $jadwalPraktek adalah string atau JSON (untuk JSON perlu diuraikan) --}}
                @if (is_string($jadwalPraktek) && !empty($jadwalPraktek))
                    
                    {{-- Jika jadwalPraktek adalah teks biasa (contoh: "Senin 08:00-12:00, Rabu 13:00-17:00") --}}
                    <p class="whitespace-pre-wrap text-gray-700 leading-relaxed p-4 bg-white rounded-xl border border-gray-200">
                        {!! nl2br(e($jadwalPraktek)) !!}
                    </p>
                    
                    {{-- Tambahkan instruksi --}}
                    <p class="mt-4 text-sm text-gray-600 italic">
                        Jika ada perubahan jadwal, harap hubungi Administrator untuk diperbarui.
                    </p>

                @else
                    {{-- Jika jadwal kosong --}}
                    <div class="p-6 text-center bg-yellow-100 rounded-xl border border-yellow-300">
                        <i class="bi bi-exclamation-triangle text-3xl text-yellow-700 mb-2"></i>
                        <p class="text-yellow-800 font-semibold">Jadwal Praktek Anda Belum Ditetapkan.</p>
                        <p class="text-sm text-yellow-700">Silakan hubungi Administrator sistem untuk mengatur jadwal Anda.</p>
                    </div>
                @endif
            </div>

        @else
             <div class="p-8 text-center bg-red-100 rounded-2xl border border-red-300 shadow-md">
                <i class="bi bi-x-octagon-fill text-4xl text-red-700 mb-3"></i>
                <p class="text-red-800 font-bold text-lg">Akses Ditolak.</p>
                <p class="text-base text-red-700">Data Dokter tidak terikat dengan akun Anda. Hubungi Administrator.</p>
            </div>
        @endif

    </div>
@endsection