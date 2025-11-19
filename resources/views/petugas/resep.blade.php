@extends('layouts.app')

@section('title', 'Antrian Resep Farmasi')

@section('content')

    <div class="space-y-6 pb-12">
        
        {{-- Flash Message --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative shadow-sm" role="alert">
                <strong class="font-bold"><i class="bi bi-check-circle-fill mr-1"></i> Berhasil!</strong> {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative shadow-sm" role="alert">
                <strong class="font-bold"><i class="bi bi-exclamation-triangle-fill mr-1"></i> Error!</strong> {{ session('error') }}
            </div>
        @endif

        {{-- Header Stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Stat 1 --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 flex items-center justify-between hover:shadow-md transition-shadow">
                <div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Menunggu Diproses</p>
                    <p class="text-3xl font-bold text-red-600 mt-1">{{ $stats['menunggu'] }}</p>
                </div>
                <div class="bg-red-50 p-3 rounded-xl text-red-600"><i class="bi bi-receipt-cutoff text-2xl"></i></div>
            </div>
            {{-- Stat 2 --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 flex items-center justify-between hover:shadow-md transition-shadow">
                <div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Selesai Hari Ini</p>
                    <p class="text-3xl font-bold text-green-600 mt-1">{{ $stats['selesai_hari_ini'] }}</p>
                </div>
                <div class="bg-green-50 p-3 rounded-xl text-green-600"><i class="bi bi-check-all text-2xl"></i></div>
            </div>
            {{-- Stat 3 --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 flex items-center justify-between hover:shadow-md transition-shadow">
                <div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Total Bulan Ini</p>
                    <p class="text-3xl font-bold text-blue-600 mt-1">{{ $stats['total_bulan_ini'] }}</p>
                </div>
                <div class="bg-blue-50 p-3 rounded-xl text-blue-600"><i class="bi bi-calendar-range-fill text-2xl"></i></div>
            </div>
            {{-- Stat 4 --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 flex items-center justify-between hover:shadow-md transition-shadow">
                <div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Stok Obat Kritis</p>
                    <p class="text-3xl font-bold text-orange-600 mt-1">{{ $stats['stok_kritis'] }}</p>
                </div>
                <div class="bg-orange-50 p-3 rounded-xl text-orange-600"><i class="bi bi-exclamation-triangle-fill text-2xl"></i></div>
            </div>
        </div>

        {{-- Tab Content --}}
        <div x-data="{ activeTab: 'menunggu' }" class="bg-white shadow-lg rounded-2xl border border-gray-200 overflow-hidden">
            
            {{-- Tab Navigation --}}
            <div class="border-b border-gray-200 bg-gray-50/50">
                <nav class="flex space-x-8 px-6" aria-label="Tabs">
                    <button @click="activeTab = 'menunggu'" 
                            :class="activeTab === 'menunggu' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 flex items-center">
                        <i class="bi bi-hourglass-split mr-2 text-lg"></i> 
                        Antrian Aktif 
                        <span class="ml-2 bg-blue-100 text-blue-600 py-0.5 px-2.5 rounded-full text-xs font-bold">{{ $antrian->count() }}</span>
                    </button>
                    <button @click="activeTab = 'selesai'" 
                            :class="activeTab === 'selesai' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 flex items-center">
                        <i class="bi bi-check-circle mr-2 text-lg"></i> 
                        Riwayat Selesai Hari Ini
                    </button>
                </nav>
            </div>

            <div class="p-6 bg-gray-50 min-h-[400px]">
                
                {{-- TAB 1: ANTRIAN MENUNGGU --}}
                <div x-show="activeTab === 'menunggu'" class="space-y-4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    
                    @forelse($antrian as $rekamMedis)
                        @php
                            // Tentukan warna border dan background berdasarkan status
                            $isDisiapkan = $rekamMedis->status_pengambilan_obat == 'disiapkan';
                            $cardClass = $isDisiapkan 
                                ? 'bg-yellow-50 border-yellow-400 ring-1 ring-yellow-200' 
                                : 'bg-white border-blue-500 ring-1 ring-gray-200';
                        @endphp

                        <div class="relative rounded-xl shadow-sm border-l-4 {{ $cardClass }} p-5 hover:shadow-md transition-all duration-200">
                            
                            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                                {{-- Informasi Pasien --}}
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h3 class="text-lg font-bold text-gray-900">
                                            {{ $rekamMedis->patient->user->name }}
                                        </h3>
                                        <span class="text-xs font-mono bg-gray-200 text-gray-600 px-2 py-0.5 rounded">RM-#{{ $rekamMedis->patient_id }}</span>
                                    </div>
                                    
                                    <div class="text-sm text-gray-600 space-y-1">
                                        <p><i class="bi bi-person-badge mr-1"></i> Dokter: <span class="font-medium text-gray-800">{{ $rekamMedis->doctor->user->name }}</span></p>
                                        <p class="text-xs text-gray-500">
                                            <i class="bi bi-clock mr-1"></i> Masuk: {{ $rekamMedis->created_at->format('H:i') }} 
                                            <span class="mx-1">•</span> 
                                            {{ $rekamMedis->created_at->diffForHumans() }}
                                        </p>
                                    </div>

                                    {{-- Status Badge --}}
                                    <div class="mt-2">
                                        @if($rekamMedis->status_pengambilan_obat == 'disiapkan')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                <span class="w-2 h-2 bg-yellow-500 rounded-full mr-1.5 animate-pulse"></span>
                                                SEDANG DISIAPKAN
                                            </span>
                                        @elseif($rekamMedis->status_pengambilan_obat == 'menunggu')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                                                <span class="w-2 h-2 bg-red-500 rounded-full mr-1.5"></span>
                                                MENUNGGU
                                            </span>
                                        @else
                                            {{-- Handle status 'tidak_ada_obat' yang lolos filter karena ada resep --}}
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                                <span class="w-2 h-2 bg-blue-500 rounded-full mr-1.5"></span>
                                                RESEP BARU
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                {{-- Action Buttons --}}
                                <div class="shrink-0">
                                    @if($rekamMedis->status_pengambilan_obat != 'disiapkan')
                                        {{-- Tombol Mulai (Muncul untuk 'menunggu' dan default) --}}
                                        <form action="{{ route('petugas.resep.process', $rekamMedis->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-5 rounded-xl transition-all shadow-sm hover:shadow flex items-center justify-center">
                                                <i class="bi bi-play-fill mr-2 text-lg"></i> Mulai Siapkan
                                            </button>
                                        </form>
                                    @else
                                        {{-- Tombol Selesai --}}
                                        <form action="{{ route('petugas.resep.complete', $rekamMedis->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" 
                                                    onclick="return confirm('Konfirmasi: Obat sudah diserahkan ke pasien dan stok akan dikurangi?')"
                                                    class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 px-5 rounded-xl transition-all shadow-sm hover:shadow flex items-center justify-center">
                                                <i class="bi bi-check-lg mr-2 text-lg"></i> Selesai & Serahkan
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            {{-- Detail Resep Obat --}}
                            <div class="mt-4 pt-4 border-t border-gray-200/60">
                                <h4 class="text-sm font-bold text-gray-700 mb-2 flex items-center">
                                    <i class="bi bi-capsule text-purple-500 mr-2"></i> Daftar Obat
                                </h4>
                                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                                    <table class="min-w-full divide-y divide-gray-100">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Nama Obat</th>
                                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Jumlah</th>
                                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Aturan</th>
                                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Stok Gudang</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                            @foreach($rekamMedis->prescriptions as $resep)
                                                <tr class="hover:bg-gray-50/50">
                                                    <td class="px-3 py-2 text-sm font-medium text-gray-800">{{ $resep->medication->nama_obat }}</td>
                                                    <td class="px-3 py-2 text-sm text-gray-700">
                                                        <span class="bg-gray-100 px-2 py-0.5 rounded font-bold">{{ $resep->jumlah }} {{ $resep->medication->satuan }}</span>
                                                    </td>
                                                    <td class="px-3 py-2 text-sm text-gray-600 italic">{{ $resep->aturan_pakai }}</td>
                                                    <td class="px-3 py-2 text-sm">
                                                        @if($resep->medication->stok < $resep->jumlah)
                                                            <span class="text-red-600 font-bold flex items-center">
                                                                <i class="bi bi-exclamation-circle-fill mr-1"></i> {{ $resep->medication->stok }} (Kurang!)
                                                            </span>
                                                        @else
                                                            <span class="text-green-600 font-medium flex items-center">
                                                                <i class="bi bi-check2 mr-1"></i> {{ $resep->medication->stok }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-16 text-center text-gray-400 bg-white rounded-xl border-2 border-dashed border-gray-200">
                            <div class="bg-gray-50 p-4 rounded-full mb-3">
                                <i class="bi bi-inbox text-4xl text-gray-300"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900">Antrian Kosong</h3>
                            <p class="text-sm mt-1">Tidak ada resep yang perlu diproses saat ini.</p>
                        </div>
                    @endforelse

                </div>

                {{-- TAB 2: RIWAYAT SELESAI --}}
                <div x-show="activeTab === 'selesai'" class="space-y-6" x-cloak>
                    <div class="overflow-hidden bg-white rounded-xl border border-gray-200 shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">ID RM</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Pasien</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Dokter</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Waktu Selesai</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($selesai as $row)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-500">#{{ $row->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $row->patient->user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $row->patient->nik }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ $row->doctor->user->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            <i class="bi bi-clock-history mr-1"></i> {{ $row->updated_at->format('H:i') }} WIB
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Selesai
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                            <p class="italic">Belum ada resep yang diselesaikan hari ini.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection