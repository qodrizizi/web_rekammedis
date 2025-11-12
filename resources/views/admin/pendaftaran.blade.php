@extends('layouts.app')

@section('title', 'Data Pendaftaran Pasien')

@section('content')

    <div class="space-y-6">
        
        <!-- Header Halaman -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <i class="bi bi-calendar-check-fill text-primary mr-3"></i> Data Pendaftaran & Janji Temu
            </h1>
            
            <a href="#" class="bg-primary hover:bg-secondary text-white font-semibold py-2 px-5 rounded-xl transition-colors duration-300 flex items-center shadow-md hover:shadow-lg">
                <i class="bi bi-calendar-plus-fill mr-2"></i> Buat Janji Temu Baru
            </a>
        </div>

        <!-- Kontainer Utama Tabel dan Filter -->
        <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-100">
            
            <!-- Area Filter dan Pencarian -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0">
                
                <!-- Search Bar -->
                <div class="w-full md:w-1/3">
                    <div class="relative">
                        <input type="text" placeholder="Cari berdasarkan Pasien, Dokter, atau Poli..." 
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                            aria-label="Cari Pendaftaran">
                        <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>

                <!-- Filter Status dan Tanggal -->
                <div class="flex items-center space-x-4">
                    
                    <select class="p-2 border border-gray-300 rounded-xl text-sm focus:ring-primary focus:border-primary transition-colors">
                        <option>Status: Menunggu</option>
                        <option>Status: Disetujui</option>
                        <option>Status: Selesai</option>
                        <option>Status: Batal</option>
                        <option>Semua Status</option>
                    </select>

                    <input type="date" class="p-2 border border-gray-300 rounded-xl text-sm focus:ring-primary focus:border-primary transition-colors" value="{{ date('Y-m-d') }}">
                </div>
            </div>

            <!-- Tabel Data Pendaftaran -->
            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-12">
                                ID
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Tanggal & Waktu
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden sm:table-cell">
                                Pasien
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden lg:table-cell">
                                Dokter & Poli
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-32">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        
                        {{-- Data Dummy --}}
                        @php
                            $dummyAppointments = [
                                ['id' => 101, 'date' => '2025-11-12', 'time' => '09:00', 'patient' => 'Rina Wijaya', 'doctor' => 'Dr. Rian', 'clinic' => 'Umum', 'status' => 'menunggu', 'complaint' => 'Sakit kepala dan demam'],
                                ['id' => 102, 'date' => '2025-11-12', 'time' => '10:30', 'patient' => 'Andi Pratama', 'doctor' => 'Dr. Amelia', 'clinic' => 'Gigi', 'status' => 'disetujui', 'complaint' => 'Sakit gigi berlubang'],
                                ['id' => 103, 'date' => '2025-11-11', 'time' => '14:00', 'patient' => 'Sari Dewi', 'doctor' => 'Dr. Santi', 'clinic' => 'Anak', 'status' => 'selesai', 'complaint' => 'Imunisasi rutin'],
                                ['id' => 104, 'date' => '2025-11-13', 'time' => '08:00', 'patient' => 'Hadi Nur', 'doctor' => 'Dr. Rian', 'clinic' => 'Umum', 'status' => 'batal', 'complaint' => 'Batuk pilek parah'],
                            ];
                        @endphp

                        {{-- Loop data Pendaftaran --}}
                        @forelse($dummyAppointments as $appointment)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #{{ $appointment['id'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::parse($appointment['date'])->isoFormat('D MMM YYYY') }}</p>
                                <p class="text-xs text-gray-500">{{ $appointment['time'] }} WIB</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 hidden sm:table-cell">
                                <p>{{ $appointment['patient'] }}</p>
                                <p class="text-xs text-gray-500 italic truncate w-40" title="{{ $appointment['complaint'] }}">Keluhan: {{ $appointment['complaint'] }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden lg:table-cell">
                                <div class="font-medium text-gray-800">{{ $appointment['doctor'] }}</div>
                                <div class="text-xs text-primary">{{ $appointment['clinic'] }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                @php
                                    $statusClass = [
                                        'menunggu' => 'bg-yellow-100 text-yellow-800',
                                        'disetujui' => 'bg-blue-100 text-blue-800',
                                        'selesai' => 'bg-green-100 text-green-800',
                                        'batal' => 'bg-red-100 text-red-800',
                                    ][$appointment['status']] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="{{ $statusClass }} px-3 py-1 rounded-full text-xs font-semibold capitalize">
                                    {{ $appointment['status'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-center space-x-1.5">
                                    {{-- Tombol Lihat Detail/Keluhan --}}
                                    <button title="Lihat Keluhan" class="text-primary hover:text-secondary p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-info-circle text-lg"></i>
                                    </button>
                                    {{-- Tombol Ubah Status --}}
                                    <button title="Ubah Status" class="text-indigo-600 hover:text-indigo-800 p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-arrow-repeat text-lg"></i>
                                    </button>
                                    {{-- Tombol Batalkan --}}
                                    <button title="Batalkan Janji" class="text-red-600 hover:text-red-800 p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-x-circle text-lg"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                Tidak ada data pendaftaran/janji temu hari ini.
                            </td>
                        </tr>
                        @endforelse
                        
                    </tbody>
                </table>
            </div>
            
            <!-- Area Pagination (Placeholder) -->
            <div class="mt-6 flex justify-between items-center">
                <p class="text-sm text-gray-600 hidden sm:block">Menampilkan 1-10 dari 48 Janji Temu</p>
                <div class="flex space-x-2 ml-auto">
                    <button class="px-3 py-1 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors disabled:opacity-50" disabled>
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button class="px-3 py-1 border border-primary bg-primary text-white rounded-lg transition-colors">
                        1
                    </button>
                    <button class="px-3 py-1 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                        2
                    </button>
                    <button class="px-3 py-1 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>

        </div>

    </div>

@endsection