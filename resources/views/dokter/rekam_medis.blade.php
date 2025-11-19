@extends('layouts.app')

@section('title', 'Riwayat Rekam Medis')

@section('content')
    <div class="space-y-8 pb-12">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-gray-200 pb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center tracking-tight">
                    <i class="bi bi-journal-medical text-teal-600 mr-3"></i>
                    Riwayat Rekam Medis
                </h1>
                <p class="text-gray-500 mt-1 ml-1">Arsip pemeriksaan pasien yang pernah Anda tangani.</p>
            </div>
            
            <div>
                <div class="inline-flex items-center bg-teal-50 border border-teal-100 text-teal-700 px-5 py-2.5 rounded-xl font-semibold shadow-sm">
                    <i class="bi bi-archive text-lg mr-2"></i>
                    <span>Total Data: </span>
                    <span class="ml-2 bg-teal-600 text-white text-xs px-2 py-1 rounded-md">{{ $medicalRecords->total() }}</span>
                </div>
            </div>
        </div>

        {{-- TABEL REKAM MEDIS --}}
        <div class="bg-white shadow-lg shadow-gray-100 rounded-2xl border border-gray-200 overflow-hidden">
            
            <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex flex-col md:flex-row justify-between items-center gap-4">
                <form action="{{ route('dokter.rekam_medis') }}" method="GET" class="relative w-full md:w-96">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari Nama, ID, atau Diagnosis..." 
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all text-sm shadow-sm">
                    <button type="submit" class="absolute left-3.5 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-teal-600">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">No. MR</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Pasien</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Diagnosis</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden md:table-cell">Tindakan</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($medicalRecords as $record)
                        <tr class="hover:bg-teal-50/30 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-teal-600 font-semibold">
                                #{{ $record->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($record->tanggal_periksa)->format('d/m/Y') }}
                                <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($record->tanggal_periksa)->format('H:i') }} WIB</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-800">{{ $record->patient->user->name ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">ID: {{ $record->patient_id }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                    {{ Str::limit($record->diagnosa, 25) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 hidden md:table-cell text-sm text-gray-600 max-w-xs truncate">
                                {{ $record->tindakan }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <button onclick="openDetailModal(this)" 
                                        data-id="{{ $record->id }}"
                                        data-nama="{{ $record->patient->user->name ?? 'N/A' }}"
                                        data-tanggal="{{ \Carbon\Carbon::parse($record->tanggal_periksa)->translatedFormat('d F Y, H:i') }}"
                                        data-keluhan="{{ $record->keluhan }}"
                                        data-diagnosa="{{ $record->diagnosa }}"
                                        data-tindakan="{{ $record->tindakan }}"
                                        data-catatan="{{ $record->catatan_dokter ?? '-' }}"
                                        class="text-gray-400 hover:text-teal-600 transition-colors p-2 rounded-full hover:bg-teal-50" 
                                        title="Lihat Detail Lengkap">
                                    <i class="bi bi-eye-fill text-xl"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="bi bi-journal-x text-4xl mb-2 text-gray-300"></i>
                                    <p>Tidak ada data rekam medis ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                <span class="text-sm text-gray-500">
                    Menampilkan <span class="font-semibold text-gray-700">{{ $medicalRecords->firstItem() ?? 0 }}</span> 
                    sampai <span class="font-semibold text-gray-700">{{ $medicalRecords->lastItem() ?? 0 }}</span> 
                    dari <span class="font-semibold text-gray-700">{{ $medicalRecords->total() }}</span> data
                </span>
                <div class="scale-90 origin-right">
                    {{ $medicalRecords->links('pagination::tailwind') }}
                </div>
            </div>
        </div>

    </div>

    {{-- MODAL DETAIL REKAM MEDIS --}}
    <div id="modalDetail" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6">
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeDetailModal()"></div>

        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto transform transition-all">
            
            <div class="bg-gradient-to-r from-teal-600 to-emerald-600 text-white px-6 py-5 sticky top-0 z-10 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold flex items-center">
                        <i class="bi bi-file-medical mr-2"></i> Detail Pemeriksaan
                    </h2>
                    <p class="text-teal-100 text-sm mt-1" id="modalNamaPasien">Nama Pasien</p>
                </div>
                <button onclick="closeDetailModal()" class="bg-white/20 hover:bg-white/30 p-2 rounded-full text-white transition-colors">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="p-6 space-y-6 bg-gray-50">
                
                <div class="flex items-center justify-between bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold">Tanggal Periksa</p>
                        <p class="text-gray-800 font-medium" id="modalTanggal">-</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500 uppercase font-bold">No. MR</p>
                        <p class="text-teal-600 font-bold font-mono text-lg" id="modalId">-</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                        <h3 class="text-sm font-bold text-gray-400 uppercase mb-2">Keluhan & Anamnesa</h3>
                        <p class="text-gray-700" id="modalKeluhan">-</p>
                    </div>

                    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm border-l-4 border-l-yellow-400">
                        <h3 class="text-sm font-bold text-gray-400 uppercase mb-2">Diagnosis</h3>
                        <p class="text-gray-800 font-bold text-lg" id="modalDiagnosa">-</p>
                    </div>

                    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                        <h3 class="text-sm font-bold text-gray-400 uppercase mb-2">Tindakan / Terapi</h3>
                        <p class="text-gray-700 whitespace-pre-line" id="modalTindakan">-</p>
                    </div>
                    
                    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                        <h3 class="text-sm font-bold text-gray-400 uppercase mb-2">Catatan Dokter</h3>
                        <p class="text-gray-600 italic" id="modalCatatan">-</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 border-t border-gray-200 flex justify-end">
                <button onclick="closeDetailModal()" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-semibold transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        function openDetailModal(button) {
            // Ambil data dari atribut tombol
            const data = button.dataset;

            // Isi modal
            document.getElementById('modalId').innerText = '#' + data.id;
            document.getElementById('modalNamaPasien').innerText = data.nama;
            document.getElementById('modalTanggal').innerText = data.tanggal;
            document.getElementById('modalKeluhan').innerText = data.keluhan;
            document.getElementById('modalDiagnosa').innerText = data.diagnosa;
            document.getElementById('modalTindakan').innerText = data.tindakan;
            document.getElementById('modalCatatan').innerText = data.catatan;

            // Tampilkan modal
            document.getElementById('modalDetail').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDetailModal() {
            document.getElementById('modalDetail').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close on ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeDetailModal();
        });
    </script>

@endsection