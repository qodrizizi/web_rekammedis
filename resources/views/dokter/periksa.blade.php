@extends('layouts.app')

@section('title', 'Pemeriksaan Medis')

@section('content')
<div class="min-h-screen bg-slate-50 pb-20">
    
    {{-- Header Navigasi --}}
    <div class="max-w-7xl mx-auto mb-6">
        <a href="{{ route('dokter.pemeriksaan') }}" class="inline-flex items-center text-slate-500 hover:text-blue-600 transition-all font-medium group">
            <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center mr-2 group-hover:border-blue-400 shadow-sm">
                <i class="bi bi-arrow-left text-sm group-hover:-translate-x-0.5 transition-transform"></i>
            </div>
            Kembali ke Daftar Antrian
        </a>
    </div>

    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        {{-- ==============================================================
             KOLOM KIRI: PROFIL PASIEN (Sticky Sidebar)
             ============================================================== --}}
        <div class="lg:col-span-4 space-y-6">
            <div class="sticky top-6 space-y-6">
                
                {{-- Kartu Pasien --}}
                <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 overflow-hidden border border-slate-100">
                    <div class="bg-gradient-to-br from-blue-600 to-indigo-700 p-6 text-white relative overflow-hidden">
                        <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 bg-white opacity-10 rounded-full blur-xl"></div>
                        <div class="relative z-10 flex items-start justify-between">
                            <div>
                                <h2 class="text-xl font-bold">{{ $appointment->patient->user->name }}</h2>
                                <p class="text-blue-100 text-sm mt-1 flex items-center">
                                    <i class="bi bi-card-heading mr-2 opacity-80"></i> NIK: {{ $appointment->patient->nik }}
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm text-lg font-bold">
                                {{ substr($appointment->patient->user->name, 0, 1) }}
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100 text-center">
                                <span class="block text-xs text-slate-400 uppercase font-bold tracking-wider mb-1">Usia</span>
                                <span class="block text-lg font-bold text-slate-700">
                                    {{ \Carbon\Carbon::parse($appointment->patient->tanggal_lahir)->age }} <span class="text-xs font-normal text-slate-500">Th</span>
                                </span>
                            </div>
                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100 text-center">
                                <span class="block text-xs text-slate-400 uppercase font-bold tracking-wider mb-1">Gender</span>
                                <span class="block text-lg font-bold text-slate-700">
                                    {{ $appointment->patient->jenis_kelamin == 'L' ? 'Pria' : 'Wanita' }}
                                </span>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase flex items-center mb-2">
                                    <i class="bi bi-chat-quote-fill mr-2 text-blue-500"></i> Keluhan Utama (Pendaftaran)
                                </label>
                                <div class="bg-yellow-50 text-yellow-800 p-4 rounded-xl border border-yellow-100 text-sm leading-relaxed italic relative">
                                    <i class="bi bi-quote absolute top-2 left-2 text-yellow-200 text-4xl -z-10"></i>
                                    "{{ $appointment->keluhan }}"
                                </div>
                            </div>
                            
                            <div class="pt-4 border-t border-slate-100 flex justify-between items-center text-sm">
                                <span class="text-slate-500">No. BPJS</span>
                                <span class="font-medium text-slate-700">{{ $appointment->patient->no_bpjs ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-slate-500">Gol. Darah</span>
                                <span class="font-medium text-slate-700">{{ $appointment->patient->gol_darah ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Riwayat Kunjungan --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
                    <h3 class="text-sm font-bold text-slate-700 mb-4 flex items-center uppercase tracking-wide">
                        <i class="bi bi-clock-history mr-2 text-slate-400 text-lg"></i> Riwayat Terakhir
                    </h3>
                    
                    <div class="space-y-4">
                        @forelse($appointment->patient->medicalRecords()->latest()->take(3)->get() as $riwayat)
                            <div class="relative pl-4 border-l-2 border-slate-200 hover:border-blue-400 transition-colors group">
                                <div class="absolute -left-[5px] top-0 w-2 h-2 rounded-full bg-slate-200 group-hover:bg-blue-400 transition-colors"></div>
                                <p class="text-xs text-slate-400 font-semibold mb-0.5">{{ \Carbon\Carbon::parse($riwayat->created_at)->format('d M Y') }}</p>
                                <p class="text-sm font-bold text-slate-700 group-hover:text-blue-600 transition-colors">{{ $riwayat->diagnosa }}</p>
                                <p class="text-xs text-slate-500 mt-1 truncate">{{ Str::limit($riwayat->tindakan, 40) }}</p>
                            </div>
                        @empty
                            <div class="text-center py-6 text-slate-400 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                                <i class="bi bi-journal-x text-2xl mb-2 block"></i>
                                <span class="text-xs">Belum ada riwayat medis</span>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>

        {{-- ==============================================================
             KOLOM KANAN: FORMULIR PEMERIKSAAN
             ============================================================== --}}
        <div class="lg:col-span-8">
            
            <form action="{{ route('dokter.periksa.store', $appointment->id) }}" method="POST" class="space-y-6">
                @csrf

                {{-- 1. TANDA VITAL (OBJECTIVE) --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mr-3">
                            <i class="bi bi-activity text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Tanda Vital</h3>
                            <p class="text-xs text-slate-500">Pemeriksaan fisik dasar</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 uppercase">Tensi</label>
                            <div class="relative">
                                <input type="text" name="tensi" placeholder="120/80" class="w-full pl-3 pr-8 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all text-sm font-semibold text-slate-700">
                                <span class="absolute right-3 top-2.5 text-xs text-slate-400">mmHg</span>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 uppercase">Suhu</label>
                            <div class="relative">
                                <input type="text" name="suhu" placeholder="36.5" class="w-full pl-3 pr-8 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all text-sm font-semibold text-slate-700">
                                <span class="absolute right-3 top-2.5 text-xs text-slate-400">°C</span>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 uppercase">Berat</label>
                            <div class="relative">
                                <input type="text" name="bb" placeholder="-" class="w-full pl-3 pr-8 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all text-sm font-semibold text-slate-700">
                                <span class="absolute right-3 top-2.5 text-xs text-slate-400">kg</span>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 uppercase">Nadi</label>
                            <div class="relative">
                                <input type="text" name="nadi" placeholder="-" class="w-full pl-3 pr-8 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all text-sm font-semibold text-slate-700">
                                <span class="absolute right-3 top-2.5 text-xs text-slate-400">bpm</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 2. PEMERIKSAAN & DIAGNOSA (ASSESSMENT) --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-3">
                            <i class="bi bi-stethoscope text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Hasil Pemeriksaan</h3>
                            <p class="text-xs text-slate-500">Anamnesa detail dan diagnosis dokter</p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Anamnesa / Keluhan Detail <span class="text-red-500">*</span></label>
                            <textarea name="keluhan" rows="3" required 
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all resize-none text-slate-700 leading-relaxed"
                                placeholder="Deskripsikan keluhan pasien secara lengkap...">{{ old('keluhan', $appointment->keluhan) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Diagnosa Medis <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <i class="bi bi-search absolute left-3 top-3 text-slate-400"></i>
                                    <input type="text" name="diagnosa" required 
                                        class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all font-semibold text-slate-800"
                                        placeholder="Cth: Febris, ISPA, Hipertensi">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Tindakan Medis (Opsional)</label>
                                <input type="text" name="tindakan" 
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all text-slate-700"
                                    placeholder="Cth: Nebulizer, Jahit Luka, Injeksi">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Catatan Dokter</label>
                            <textarea name="catatan" rows="2" 
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all resize-none text-slate-700"
                                placeholder="Instruksi khusus atau catatan tambahan..."></textarea>
                        </div>
                    </div>
                </div>

                {{-- 3. RESEP OBAT (PLAN) --}}
                <div x-data="resepHandler()" class="bg-white rounded-2xl shadow-lg shadow-blue-100/50 border border-blue-100 overflow-hidden">
                    <div class="bg-blue-50/50 p-6 border-b border-blue-100 flex justify-between items-center">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-white border border-blue-200 text-blue-600 flex items-center justify-center mr-3 shadow-sm">
                                <i class="bi bi-capsule text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-800">Resep Obat</h3>
                                <p class="text-xs text-slate-500">Akan diteruskan ke instalasi farmasi</p>
                            </div>
                        </div>
                        <button type="button" @click="addRow()" class="text-sm bg-white border border-blue-200 text-blue-700 hover:bg-blue-50 px-4 py-2 rounded-xl font-semibold transition-all shadow-sm flex items-center">
                            <i class="bi bi-plus-lg mr-2"></i> Tambah Obat
                        </button>
                    </div>

                    <div class="p-6">
                        {{-- Header Tabel --}}
                        <div class="grid grid-cols-12 gap-4 mb-3 px-2 text-xs font-bold text-slate-400 uppercase tracking-wider">
                            <div class="col-span-5">Nama Obat</div>
                            <div class="col-span-2">Jumlah</div>
                            <div class="col-span-4">Aturan Pakai</div>
                            <div class="col-span-1 text-center">Hapus</div>
                        </div>

                        {{-- Rows Dinamis --}}
                        <div class="space-y-3">
                            <template x-for="(row, index) in rows" :key="index">
                                <div class="grid grid-cols-12 gap-4 items-start animate-slide-in group">
                                    
                                    {{-- Pilih Obat --}}
                                    <div class="col-span-5">
                                        <select :name="'resep['+index+'][medication_id]'" required 
                                            class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-slate-700">
                                            <option value="">-- Pilih Obat --</option>
                                            @foreach($medications as $med)
                                                <option value="{{ $med->id }}">{{ $med->nama_obat }} (Stok: {{ $med->stok }})</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Jumlah --}}
                                    <div class="col-span-2">
                                        <input type="number" :name="'resep['+index+'][jumlah]'" placeholder="0" required min="1" 
                                            class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 transition-all text-center font-semibold text-slate-700">
                                    </div>

                                    {{-- Aturan Pakai --}}
                                    <div class="col-span-4">
                                        <input type="text" :name="'resep['+index+'][aturan_pakai]'" placeholder="3x1 Sesudah makan" required 
                                            class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 transition-all text-slate-700">
                                    </div>

                                    {{-- Hapus --}}
                                    <div class="col-span-1 flex justify-center pt-1">
                                        <button type="button" @click="removeRow(index)" 
                                            class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-300 hover:text-red-500 hover:bg-red-50 transition-all">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </div>
                                </div>
                            </template>
                            
                            {{-- State Kosong --}}
                            <div x-show="rows.length === 0" class="py-8 text-center border-2 border-dashed border-slate-200 rounded-xl bg-slate-50/50">
                                <p class="text-slate-400 text-sm mb-2">Belum ada obat yang diresepkan.</p>
                                <p class="text-xs text-slate-300">Klik tombol "Tambah Obat" di atas.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- FOOTER ACTION --}}
                <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 p-4 lg:pl-80 z-40 shadow-[0_-4px_20px_rgba(0,0,0,0.05)]">
                    <div class="max-w-7xl mx-auto flex justify-between items-center">
                        <div class="text-sm text-slate-500 hidden sm:block">
                            Pastikan diagnosa dan resep sudah sesuai sebelum menyimpan.
                        </div>
                        <div class="flex space-x-4 w-full sm:w-auto">
                            <a href="{{ route('dokter.pemeriksaan') }}" class="w-full sm:w-auto px-6 py-3 rounded-xl border border-slate-300 text-slate-600 font-bold hover:bg-slate-50 transition-colors text-center">
                                Batal
                            </a>
                            <button type="submit" class="w-full sm:w-auto px-8 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-1 flex items-center justify-center">
                                <i class="bi bi-check-circle-fill mr-2"></i> Simpan & Selesai
                            </button>
                        </div>
                    </div>
                </div>
                
                {{-- Spacer untuk footer fixed --}}
                <div class="h-24"></div>

            </form>
        </div>
    </div>
</div>

{{-- Script Alpine.js --}}
<script>
    function resepHandler() {
        return {
            rows: [],
            addRow() {
                this.rows.push({ medication_id: '', jumlah: '', aturan_pakai: '' });
            },
            removeRow(index) {
                this.rows.splice(index, 1);
            }
        }
    }
</script>

<style>
    /* Animasi Halus */
    .animate-slide-in {
        animation: slideIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    /* Custom Scrollbar untuk Textarea */
    textarea::-webkit-scrollbar { width: 6px; }
    textarea::-webkit-scrollbar-track { background: #f1f5f9; }
    textarea::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
    textarea::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
@endsection