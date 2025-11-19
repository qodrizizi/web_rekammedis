@extends('layouts.app')

@section('title', 'Dashboard Farmasi')

@section('content')

<div class="space-y-8 pb-12">
    
    {{-- HEADER --}}
    <header class="bg-white p-6 rounded-2xl shadow-xl border-l-8 border-teal-500 transition duration-300 hover:shadow-2xl relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-3xl font-extrabold text-gray-800 flex items-center">
                <i class="bi bi-prescription2 text-teal-600 mr-3"></i> Dashboard Farmasi & Obat
            </h1>
            <p class="text-gray-600 mt-2">
                Halo, <span class="font-bold text-gray-800">{{ auth()->user()->name }}</span>. 
                Pantau stok obat dan selesaikan antrian resep dengan teliti.
            </p>
        </div>
        {{-- Dekorasi Background --}}
        <div class="absolute right-0 top-0 -mt-4 -mr-4 opacity-10">
            <i class="bi bi-capsule text-9xl text-teal-600"></i>
        </div>
    </header>

    {{-- STATS CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        {{-- Card 1: Resep Menunggu (Paling Penting - Merah) --}}
        <div class="bg-white p-6 rounded-2xl shadow-md border-b-4 border-red-500 hover:-translate-y-1 transition duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Resep Menunggu</p>
                    <p class="text-4xl font-extrabold text-gray-800 mt-1">{{ $stats['resep_pending'] }}</p>
                </div>
                <div class="bg-red-50 p-3 rounded-xl text-red-500">
                    <i class="bi bi-hourglass-split text-2xl"></i>
                </div>
            </div>
            <p class="text-xs text-red-500 mt-3 font-medium">Perlu segera disiapkan!</p>
        </div>

        {{-- Card 2: Stok Kritis (Warning - Orange) --}}
        <div class="bg-white p-6 rounded-2xl shadow-md border-b-4 border-orange-500 hover:-translate-y-1 transition duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Stok Obat Kritis</p>
                    <p class="text-4xl font-extrabold text-gray-800 mt-1">{{ $stats['stok_kritis'] }}</p>
                </div>
                <div class="bg-orange-50 p-3 rounded-xl text-orange-500">
                    <i class="bi bi-exclamation-triangle-fill text-2xl"></i>
                </div>
            </div>
            <p class="text-xs text-orange-500 mt-3 font-medium">Segera ajukan pengadaan.</p>
        </div>

        {{-- Card 3: Selesai Hari Ini (Success - Hijau) --}}
        <div class="bg-white p-6 rounded-2xl shadow-md border-b-4 border-green-500 hover:-translate-y-1 transition duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Selesai Hari Ini</p>
                    <p class="text-4xl font-extrabold text-gray-800 mt-1">{{ $stats['resep_selesai_today'] }}</p>
                </div>
                <div class="bg-green-50 p-3 rounded-xl text-green-500">
                    <i class="bi bi-check-circle-fill text-2xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-3">Kerja bagus!</p>
        </div>

        {{-- Card 4: Total Item (Info - Biru) --}}
        <div class="bg-white p-6 rounded-2xl shadow-md border-b-4 border-blue-500 hover:-translate-y-1 transition duration-300">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Jenis Obat</p>
                    <p class="text-4xl font-extrabold text-gray-800 mt-1">{{ $stats['total_item_obat'] }}</p>
                </div>
                <div class="bg-blue-50 p-3 rounded-xl text-blue-500">
                    <i class="bi bi-box-seam-fill text-2xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-3">Terdaftar di database.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- KOLOM KIRI: Antrian Resep --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h2 class="text-lg font-bold text-gray-800 flex items-center">
                        <i class="bi bi-list-task text-teal-600 mr-2"></i> Antrian Resep Prioritas
                    </h2>
                    <a href="{{ route('petugas.resep') }}" class="text-sm text-teal-600 font-bold hover:underline">Lihat Semua &rarr;</a>
                </div>
                
                <div class="divide-y divide-gray-100">
                    @forelse($antrianResep as $resep)
                        <div class="p-4 hover:bg-teal-50/30 transition flex items-center justify-between group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 font-bold text-sm">
                                    {{ substr($resep->patient->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 text-sm">{{ $resep->patient->user->name }}</p>
                                    <p class="text-xs text-gray-500">
                                        Dokter: {{ $resep->doctor->user->name }} • 
                                        <span class="text-gray-400">{{ $resep->created_at->diffForHumans() }}</span>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3">
                                @if($resep->status_pengambilan_obat == 'disiapkan')
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 animate-pulse">
                                        Sedang Disiapkan
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
                                        Menunggu
                                    </span>
                                @endif
                                
                                <form action="{{ route('petugas.resep.process', $resep->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-teal-600 hover:text-white text-gray-400 flex items-center justify-center transition shadow-sm" title="Proses">
                                        <i class="bi bi-play-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <div class="inline-block p-4 rounded-full bg-green-50 text-green-500 mb-3">
                                <i class="bi bi-cup-hot text-3xl"></i>
                            </div>
                            <p class="text-gray-800 font-medium">Tidak ada antrian resep.</p>
                            <p class="text-sm text-gray-500">Anda bisa istirahat sejenak atau cek stok obat.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        
        {{-- KOLOM KANAN: Stok Menipis --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg border border-red-100 h-full">
                <div class="p-5 border-b border-red-50 bg-red-50/50">
                    <h2 class="text-lg font-bold text-gray-800 flex items-center">
                        <i class="bi bi-bell-fill text-red-500 mr-2 animate-bounce"></i> Stok Obat Menipis
                    </h2>
                </div>
                
                <div class="p-4 space-y-3">
                    @forelse($obatMenipis as $obat)
                        <div class="flex items-center justify-between p-3 bg-white border border-gray-100 rounded-xl shadow-sm hover:shadow-md hover:border-red-200 transition">
                            <div>
                                <p class="font-bold text-gray-800 text-sm">{{ $obat->nama_obat }}</p>
                                <p class="text-xs text-gray-500">{{ $obat->kode_obat }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-red-600">{{ $obat->stok }}</p>
                                <p class="text-[10px] text-gray-400 uppercase font-bold">{{ $obat->satuan }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <i class="bi bi-check-circle text-4xl text-green-400 mb-2 block"></i>
                            <p class="text-sm text-gray-500">Semua stok aman.</p>
                        </div>
                    @endforelse
                </div>

                <div class="p-4 border-t border-gray-100 bg-gray-50 rounded-b-2xl text-center">
                    <a href="{{ route('petugas.obat') }}" class="text-xs font-bold text-gray-500 hover:text-gray-800 uppercase tracking-wider">
                        Lihat Semua Data Obat
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection