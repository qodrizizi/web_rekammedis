@extends('layouts.app')

@section('title', 'Data Pasien Saya')

@section('content')
    <div class="space-y-8 pb-12">

        {{-- 1. PAGE HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-gray-200 pb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center tracking-tight">
                    <i class="bi bi-people-fill text-blue-600 mr-3"></i>
                    Pasien & Antrian Saya
                </h1>
                <p class="text-gray-500 mt-1 ml-1">Kelola antrian harian dan riwayat medis pasien Anda.</p>
            </div>
            
            <div>
                <div class="inline-flex items-center bg-blue-50 border border-blue-100 text-blue-700 px-5 py-2.5 rounded-xl font-semibold shadow-sm">
                    <i class="bi bi-calendar-check text-lg mr-2"></i>
                    <span>Total Antrian Hari Ini: </span>
                    <span class="ml-2 bg-blue-600 text-white text-xs px-2 py-1 rounded-md">{{ $antrianHariIni->count() }}</span>
                </div>
            </div>
        </div>

        {{-- 2. SECTION ANTRIAN HARI INI --}}
        <section>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800 flex items-center">
                    <span class="bg-blue-600 w-1 h-6 rounded-r mr-3"></span>
                    Antrian Pemeriksaan
                </h2>
                <span class="text-sm font-medium text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                    {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                </span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @forelse($antrianHariIni as $antrian)
                        @php
                            $pasien = $antrian->patient;
                            $namaPasien = $pasien->user->name ?? 'Pasien Tidak Dikenal';
                            $usia = $pasien->tanggal_lahir ? \Carbon\Carbon::parse($pasien->tanggal_lahir)->age . ' th' : '-';
                            
                            // Styling Logic
                            $status = $antrian->status;
                            $statusMeta = match($status) {
                                'menunggu'  => ['text' => 'Menunggu', 'class' => 'bg-gray-100 text-gray-600 border-gray-200', 'border' => 'border-l-gray-400'],
                                'disetujui' => ['text' => 'Siap Diperiksa', 'class' => 'bg-green-50 text-green-700 border-green-200', 'border' => 'border-l-green-500'],
                                'selesai'   => ['text' => 'Selesai', 'class' => 'bg-blue-50 text-blue-700 border-blue-200', 'border' => 'border-l-blue-500'],
                                'batal'     => ['text' => 'Dibatalkan', 'class' => 'bg-red-50 text-red-700 border-red-200', 'border' => 'border-l-red-500'],
                                default     => ['text' => 'Unknown', 'class' => 'bg-gray-100', 'border' => 'border-l-gray-300']
                            };
                        @endphp

                        <div class="relative bg-white border border-gray-200 rounded-xl p-5 shadow-sm hover:shadow-md transition-all duration-200 border-l-4 {{ $statusMeta['border'] }}">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h3 class="font-bold text-gray-800 truncate text-lg" title="{{ $namaPasien }}">{{ $namaPasien }}</h3>
                                    <div class="flex items-center text-xs text-gray-500 mt-1 space-x-2">
                                        <span class="bg-gray-100 px-2 py-0.5 rounded">ID: {{ $pasien->id }}</span>
                                        <span>•</span>
                                        <span>{{ $pasien->jenis_kelamin }}</span>
                                        <span>•</span>
                                        <span>{{ $usia }}</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-blue-600 font-mono">
                                        {{ \Carbon\Carbon::parse($antrian->jam_kunjungan)->format('H:i') }}
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-3 mb-4 border border-gray-100">
                                <p class="text-xs text-gray-500 font-bold uppercase mb-1">Keluhan:</p>
                                <p class="text-sm text-gray-700 line-clamp-2">{{ $antrian->keluhan ?? '-' }}</p>
                            </div>

                            <div class="flex justify-between items-center pt-2 border-t border-gray-100 mt-2">
                                <span class="text-xs font-bold px-3 py-1.5 rounded-full border {{ $statusMeta['class'] }}">
                                    {{ $statusMeta['text'] }}
                                </span>

                                <div class="flex space-x-2">
                                    <button onclick="openRekamMedisModal('{{ $pasien->id }}', '{{ $namaPasien }}')" 
                                            class="p-2 rounded-lg text-gray-500 hover:text-blue-600 hover:bg-blue-50 transition-colors" 
                                            title="Lihat Rekam Medis">
                                        <i class="bi bi-journal-medical text-xl"></i>
                                    </button>

                                    @if ($status != 'selesai' && $status != 'batal')
                                        <a href="{{ route('dokter.periksa', $antrian->id) }}" 
                                           class="flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-sm transition-all hover:shadow-md decoration-transparent no-underline">
                                            <i class="bi bi-stethoscope mr-2"></i> Periksa
                                        </a>
                                    @else
                                        <span class="flex items-center text-green-600 text-sm font-medium px-3">
                                            <i class="bi bi-check-circle-fill mr-1"></i> Done
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full flex flex-col items-center justify-center py-12 bg-gray-50 rounded-xl border border-dashed border-gray-300 text-gray-500">
                            <i class="bi bi-calendar-x text-4xl mb-3 text-gray-400"></i>
                            <p class="font-medium">Tidak ada pasien dalam antrian pemeriksaan hari ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        {{-- 3. SECTION RIWAYAT PASIEN --}}
        <section>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800 flex items-center">
                    <span class="bg-teal-500 w-1 h-6 rounded-r mr-3"></span>
                    Database Pasien Saya
                    <span class="ml-2 text-sm font-normal text-gray-500">({{ $patients->total() }} orang)</span>
                </h2>
            </div>

            <div class="bg-white shadow-lg shadow-gray-100 rounded-2xl border border-gray-200 overflow-hidden">
                
                <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="relative w-full md:w-96">
                        <input type="text" 
                               placeholder="Cari Nama atau ID Pasien..." 
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm shadow-sm"
                               aria-label="Cari Pasien">
                        <i class="bi bi-search absolute left-3.5 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>

                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <select class="w-full md:w-auto pl-3 pr-8 py-2.5 border border-gray-300 rounded-xl text-sm bg-white focus:ring-2 focus:ring-blue-500 transition-colors cursor-pointer">
                            <option>Urutkan: Terbaru</option>
                            <option>Urutkan: Nama (A-Z)</option>
                            <option>Urutkan: Diagnosis</option>
                        </select>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">ID Pasien</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Identitas Pasien</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden sm:table-cell">Tgl Lahir</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden lg:table-cell">Kunjungan Terakhir</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden md:table-cell">Diagnosis Terakhir</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($patients as $patient)
                                @php
                                    $lastRecord = $patient->medicalRecords->first(); // Asumsi sudah sorted DESC di controller
                                    $lastCheck = $lastRecord ? \Carbon\Carbon::parse($lastRecord->tanggal_periksa)->diffForHumans() : null;
                                    $diagnosis = $lastRecord ? Str::limit($lastRecord->diagnosa, 20) : 'Belum diperiksa';
                                    $namaPasien = $patient->user->name ?? 'No Name';
                                @endphp
                                <tr class="hover:bg-blue-50/30 transition-colors group">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-blue-600 font-semibold">
                                        #{{ $patient->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold mr-3">
                                                {{ substr($namaPasien, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900">{{ $namaPasien }}</div>
                                                <div class="text-xs text-gray-500">{{ $patient->jenis_kelamin }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 hidden sm:table-cell">
                                        {{ $patient->tanggal_lahir ? \Carbon\Carbon::parse($patient->tanggal_lahir)->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap hidden lg:table-cell">
                                        @if($lastCheck)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <i class="bi bi-clock-history mr-1"></i> {{ $lastCheck }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Belum ada data</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $lastRecord ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-500' }}">
                                            {{ $diagnosis }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <button onclick="openRekamMedisModal('{{ $patient->id }}', '{{ $namaPasien }}')" 
                                                class="text-gray-400 hover:text-blue-600 transition-colors p-2 rounded-full hover:bg-blue-50" 
                                                title="Lihat Detail Rekam Medis">
                                            <i class="bi bi-journal-text text-xl"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="bi bi-inbox text-4xl mb-2 text-gray-300"></i>
                                            <p>Belum ada data pasien yang terhubung dengan Anda.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                    <span class="text-sm text-gray-500">
                        Menampilkan <span class="font-semibold text-gray-700">{{ $patients->firstItem() ?? 0 }}</span> 
                        sampai <span class="font-semibold text-gray-700">{{ $patients->lastItem() ?? 0 }}</span> 
                        dari <span class="font-semibold text-gray-700">{{ $patients->total() }}</span> data
                    </span>
                    <div class="scale-90 origin-right">
                        {{ $patients->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- ==================================================== --}}
    {{-- MODAL PEMERIKSAAN (FORM) --}}
    {{-- ==================================================== --}}
    <div id="modalPemeriksaan" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6">
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closePemeriksaanModal()"></div>

        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto transform transition-all">
            <form action="{{ route('dokter.simpan-pemeriksaan') }}" method="POST"> 
                @csrf
                <input type="hidden" name="patient_id" id="modalPatientId">
                <input type="hidden" name="doctor_id" value="{{ auth()->user()->doctor->id ?? 1 }}">

                <div class="sticky top-0 z-10 bg-white border-b border-gray-100 px-6 py-4 flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800 flex items-center">
                            <div class="bg-blue-100 p-2 rounded-lg mr-3 text-blue-600">
                                <i class="bi bi-clipboard2-pulse-fill"></i>
                            </div>
                            Input Pemeriksaan
                        </h2>
                        <p class="text-sm text-gray-500 mt-0.5 ml-[3.25rem]" id="modalPatientInfo">Memuat data pasien...</p>
                    </div>
                    <button type="button" onclick="closePemeriksaanModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="bi bi-x-lg text-xl"></i>
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Status Pemeriksaan</label>
                            <select name="status_pemeriksaan" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                <option value="sedang_diperiksa">Sedang Diperiksa</option>
                                <option value="selesai" selected>Selesai & Simpan</option>
                            </select>
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    <div class="bg-blue-50/50 rounded-xl p-5 border border-blue-100">
                        <h3 class="text-sm font-bold text-blue-800 uppercase tracking-wide mb-3 flex items-center">
                            <i class="bi bi-activity mr-2"></i> Tanda-tanda Vital
                        </h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <label class="text-xs text-gray-500 font-semibold block mb-1">Tensi (mmHg)</label>
                                <input type="text" name="vital_signs[tensi]" placeholder="120/80" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 font-semibold block mb-1">Suhu (°C)</label>
                                <input type="text" name="vital_signs[suhu]" placeholder="36.5" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 font-semibold block mb-1">Nadi (x/mnt)</label>
                                <input type="text" name="vital_signs[nadi]" placeholder="80" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 font-semibold block mb-1">BB (kg)</label>
                                <input type="text" name="vital_signs[bb]" placeholder="65" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Keluhan Utama</label>
                            <textarea id="keluhanPasien" name="keluhan_from_appointment" rows="2" readonly
                                class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-gray-600 text-sm resize-none focus:outline-none"
                                placeholder="Keluhan dari pendaftaran..."></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Anamnesa & Fisik</label>
                            <textarea name="anamnesa_fisik" rows="3" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm"
                                placeholder="Hasil anamnesa dan pemeriksaan fisik..."></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Diagnosis <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="diagnosa" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all font-medium"
                                placeholder="Contoh: ISPA, Hipertensi, Dyspepsia">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Terapi / Resep <span class="text-red-500">*</span>
                                </label>
                                <textarea name="tindakan" required rows="4" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm"
                                    placeholder="R/ Paracetamol 500mg No. X..."></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan Tambahan</label>
                                <textarea name="catatan_dokter" rows="4" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm"
                                    placeholder="Saran kontrol ulang, edukasi, dll..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl border-t border-gray-200">
                    <button type="button" onclick="closePemeriksaanModal()" 
                        class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-colors shadow-sm">
                        Batal
                    </button>
                    <button type="submit" 
                        class="px-5 py-2.5 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition-all shadow-md hover:shadow-lg flex items-center">
                        <i class="bi bi-save2-fill mr-2"></i> Simpan Hasil
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ==================================================== --}}
    {{-- MODAL RIWAYAT REKAM MEDIS --}}
    {{-- ==================================================== --}}
    <div id="modalRekamMedis" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6">
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeRekamMedisModal()"></div>

        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[85vh] overflow-hidden flex flex-col transform transition-all">
            
            <div class="bg-gradient-to-r from-teal-600 to-emerald-600 text-white px-6 py-5 shrink-0 flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-bold flex items-center">
                        <i class="bi bi-journal-medical mr-3 text-2xl"></i> 
                        Rekam Medis Pasien
                    </h2>
                    <p class="text-teal-100 mt-1 font-medium text-lg" id="modalRekamMedisName">Loading Name...</p>
                </div>
                <button onclick="closeRekamMedisModal()" class="bg-white/20 hover:bg-white/30 p-2 rounded-full transition-colors">
                    <i class="bi bi-x-lg text-white"></i>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-6 bg-gray-50">
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="text-center md:text-left">
                        <span class="text-xs text-gray-400 font-bold uppercase">NIK</span>
                        <p class="font-bold text-gray-800" id="rmNik">-</p>
                    </div>
                    <div class="text-center md:text-left">
                        <span class="text-xs text-gray-400 font-bold uppercase">Gender</span>
                        <p class="font-bold text-gray-800" id="rmJK">-</p>
                    </div>
                    <div class="text-center md:text-left">
                        <span class="text-xs text-gray-400 font-bold uppercase">Usia</span>
                        <p class="font-bold text-gray-800" id="rmUsia">-</p>
                    </div>
                    <div class="text-center md:text-left">
                        <span class="text-xs text-gray-400 font-bold uppercase">Gol. Darah</span>
                        <p class="font-bold text-gray-800" id="rmGoldar">-</p>
                    </div>
                </div>

                <div id="rmLoading" class="py-12 text-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-4 border-gray-200 border-t-teal-600 mx-auto mb-4"></div>
                    <p class="text-gray-500 font-medium">Mengambil riwayat medis...</p>
                </div>

                <div id="rmContent" class="space-y-0 hidden">
                    </div>

                <div id="rmEmpty" class="hidden py-12 text-center border-2 border-dashed border-gray-300 rounded-xl">
                    <i class="bi bi-clipboard-x text-5xl text-gray-300 mb-3 inline-block"></i>
                    <p class="text-gray-500">Pasien ini belum memiliki riwayat pemeriksaan.</p>
                </div>
            </div>

            <div class="bg-white p-4 border-t border-gray-200 shrink-0 flex justify-end">
                <button onclick="closeRekamMedisModal()" class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-semibold transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- JAVASCRIPT LOGIC --}}
    <script>
        // --- HELPER FUNCTIONS ---
        const formatList = (items) => {
            if (!items || items.length === 0) return '<span class="text-gray-400 text-xs italic">Tidak ada data</span>';
            return `<ul class="list-disc list-inside text-sm text-gray-700 space-y-1 mt-1">${items.map(i => `<li>${i}</li>`).join('')}</ul>`;
        };

        // --- MODAL REKAM MEDIS ---
        async function openRekamMedisModal(patientId, patientName) {
            const modal = document.getElementById('modalRekamMedis');
            const loading = document.getElementById('rmLoading');
            const content = document.getElementById('rmContent');
            const empty = document.getElementById('rmEmpty');
            
            // Reset UI
            document.getElementById('modalRekamMedisName').textContent = patientName;
            ['rmNik', 'rmJK', 'rmUsia', 'rmGoldar'].forEach(id => document.getElementById(id).textContent = '-');
            
            modal.classList.remove('hidden');
            loading.classList.remove('hidden');
            content.classList.add('hidden');
            empty.classList.add('hidden');
            document.body.style.overflow = 'hidden'; // Prevent body scroll

            try {
                // Fetch Data
                const response = await fetch(`/dokter/pasien/${patientId}/history`);
                if (!response.ok) throw new Error('Gagal mengambil data');
                
                const data = await response.json();

                // Update Header Info
                document.getElementById('rmNik').textContent = data.nik || '-';
                document.getElementById('rmJK').textContent = data.jenis_kelamin || '-';
                document.getElementById('rmUsia').textContent = data.usia || '-';
                document.getElementById('rmGoldar').textContent = data.gol_darah || '-';

                // Build Timeline
                if (data.riwayat && data.riwayat.length > 0) {
                    content.innerHTML = data.riwayat.map((item, index) => `
                        <div class="flex gap-4 pb-8 last:pb-0 relative group">
                            <div class="absolute left-[19px] top-8 bottom-0 w-0.5 bg-gray-200 group-last:hidden"></div>
                            
                            <div class="shrink-0 w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center border-4 border-white shadow-sm z-10">
                                <i class="bi bi-calendar-check text-teal-600"></i>
                            </div>

                            <div class="flex-1 bg-white border border-gray-200 rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow">
                                <div class="flex flex-col sm:flex-row justify-between sm:items-center border-b border-gray-100 pb-3 mb-3">
                                    <div>
                                        <span class="text-xs font-bold text-teal-600 bg-teal-50 px-2 py-1 rounded uppercase tracking-wider">${item.tanggal}</span>
                                        <h4 class="font-bold text-gray-800 text-lg mt-1">${item.diagnosa}</h4>
                                    </div>
                                    <div class="mt-2 sm:mt-0 text-right">
                                        <p class="text-xs text-gray-500">Dokter Pemeriksa</p>
                                        <p class="text-sm font-medium text-gray-700">${item.dokter}</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-3">
                                        <div>
                                            <h5 class="text-xs font-bold text-gray-400 uppercase mb-1">Keluhan & Anamnesa</h5>
                                            <p class="text-sm text-gray-700 bg-gray-50 p-2 rounded border border-gray-100">${item.keluhan || '-'}</p>
                                        </div>
                                        <div>
                                            <h5 class="text-xs font-bold text-gray-400 uppercase mb-1">Tindakan</h5>
                                            <p class="text-sm text-gray-700">${item.tindakan || '-'}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-3">
                                        <div>
                                            <h5 class="text-xs font-bold text-gray-400 uppercase mb-1">Resep Obat</h5>
                                            <div class="bg-blue-50 p-2 rounded border border-blue-100">
                                                ${formatList(item.resep)}
                                            </div>
                                        </div>
                                        ${item.catatan ? `
                                            <div>
                                                <h5 class="text-xs font-bold text-gray-400 uppercase mb-1">Catatan</h5>
                                                <p class="text-sm text-gray-600 italic">"${item.catatan}"</p>
                                            </div>
                                        ` : ''}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `).join('');
                    
                    content.classList.remove('hidden');
                } else {
                    empty.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memuat data. Silakan coba lagi.');
            } finally {
                loading.classList.add('hidden');
            }
        }

        function closeRekamMedisModal() {
            document.getElementById('modalRekamMedis').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // --- MODAL PEMERIKSAAN (Optional Functionality) ---
        function openPemeriksaanModal(patientId, patientName, keluhan = '') {
            const modal = document.getElementById('modalPemeriksaan');
            document.getElementById('modalPatientId').value = patientId;
            document.getElementById('modalPatientInfo').textContent = 'Pasien: ' + patientName;
            document.getElementById('keluhanPasien').value = keluhan;
            
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closePemeriksaanModal() {
            document.getElementById('modalPemeriksaan').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close on ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeRekamMedisModal();
                closePemeriksaanModal();
            }
        });
    </script>
@endsection