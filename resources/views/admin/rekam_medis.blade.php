{{-- resources/views/admin/rekam_medis.blade.php --}}

@extends('layouts.app')

@section('title', 'Data Rekam Medis')

@section('content')

    <div class="space-y-6">
        
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <i class="bi bi-clipboard2-pulse-fill text-primary mr-3"></i> Data Rekam Medis
            </h1>
            
            <button onclick="openModal('modalAddRM')" class="bg-primary hover:bg-secondary text-white font-semibold py-2 px-5 rounded-xl transition-colors duration-300 flex items-center shadow-md hover:shadow-lg">
                <i class="bi bi-plus-lg mr-2"></i> Tambah Rekam Medis Baru
            </button>
        </div>

        {{-- Notifikasi Sukses & Error --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative shadow-md" role="alert">
                <strong class="font-bold">Berhasil!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative shadow-md">
                <strong class="font-bold">Gagal menyimpan data!</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-100">
            
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0">
                
                <div class="w-full md:w-1/3">
                    <div class="relative">
                        <input type="text" placeholder="Cari berdasarkan Pasien, Dokter, atau Diagnosa..." 
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                            aria-label="Cari Rekam Medis">
                        <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600 hidden sm:block">Filter berdasarkan:</span>
                    
                    <select class="p-2 border border-gray-300 rounded-xl text-sm focus:ring-primary focus:border-primary transition-colors">
                        <option>Semua Poli</option>
                        @foreach($clinics as $clinic)
                            <option value="{{ $clinic->id }}">{{ $clinic->nama_poli }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-12">Tgl. Periksa</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pasien</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden sm:table-cell">Dokter / Poli</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Diagnosa Utama</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        
                        @forelse($records as $record)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700">
                                {{ \Carbon\Carbon::parse($record->tanggal_periksa)->isoFormat('D MMM YYYY') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm font-semibold text-gray-900">{{ $record->patient->user->name ?? 'Pasien Dihapus' }}</p>
                                <p class="text-xs text-gray-500">RM-{{ str_pad($record->id, 5, '0', STR_PAD_LEFT) }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden sm:table-cell">
                                <div class="font-medium text-gray-800">{{ $record->doctor->user->name ?? 'Dokter Dihapus' }}</div>
                                <div class="text-xs text-primary">{{ $record->clinic->nama_poli ?? 'Poli Dihapus' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-normal text-sm text-gray-800 font-medium line-clamp-2">
                                {{ $record->diagnosa }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-center space-x-1.5">
                                    
                                    {{-- Data JSON tunggal Rekam Medis --}}
                                    @php
                                        $dataJson = [
                                            'id' => $record->id,
                                            'patient_name' => $record->patient->user->name ?? 'N/A',
                                            'doctor_name' => $record->doctor->user->name ?? 'N/A',
                                            'clinic_name' => $record->clinic->nama_poli ?? 'N/A',
                                            'patient_id' => $record->patient_id,
                                            'doctor_id' => $record->doctor_id,
                                            'clinic_id' => $record->clinic_id,
                                            'tanggal_periksa' => $record->tanggal_periksa,
                                            'keluhan' => $record->keluhan,
                                            'diagnosa' => $record->diagnosa,
                                            'tindakan' => $record->tindakan,
                                            'catatan_dokter' => $record->catatan_dokter,
                                        ];
                                    @endphp

                                    {{-- Tombol View (Memanggil Modal) --}}
                                    <button 
                                        type="button"
                                        data-record='@json($dataJson)'
                                        onclick="viewRMFromData(this)" 
                                        title="Lihat Detail Rekam Medis" 
                                        class="text-primary hover:text-secondary p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-file-earmark-medical text-lg"></i>
                                    </button>
                                    
                                    {{-- Tombol Edit (Memanggil Modal) --}}
                                    <button 
                                        type="button"
                                        data-record='@json($dataJson)'
                                        onclick="editRMFromData(this)" 
                                        title="Edit Catatan" 
                                        class="text-yellow-600 hover:text-yellow-800 p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-pencil-square text-lg"></i>
                                    </button>
                                    
                                    {{-- Tombol Delete (Memanggil Modal) --}}
                                    <button onclick='deleteRM("{{ $record->id }}", "RM-{{ str_pad($record->id, 5, '0', STR_PAD_LEFT) }}")' title="Hapus Catatan" class="text-red-600 hover:text-red-800 p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-trash text-lg"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                Tidak ada data rekam medis yang ditemukan.
                            </td>
                        </tr>
                        @endforelse
                        
                    </tbody>
                </table>
            </div>
            
            <div class="mt-6 flex justify-center">
                <div class="flex space-x-2">
                    {{ $records->links('pagination::tailwind') }} 
                </div>
            </div>

        </div>

    </div>

    {{-- MODALS SECTION --}}

    {{-- Modal Tambah Rekam Medis Baru (Mirip Modal Add Obat) --}}
    <div id="modalAddRM" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 rounded-t-2xl">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="bi bi-plus-circle-fill text-primary mr-3"></i> Tambah Rekam Medis Baru
                    </h2>
                    <button type="button" onclick="closeModal('modalAddRM')" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="bi bi-x-lg text-2xl"></i>
                    </button>
                </div>
            </div>
            
            <form id="formAddRM" action="{{ route('admin.rekam_medis.store') }}" method="POST" class="p-6 space-y-6">
                @csrf
                
                <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">Informasi Kunjungan</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Pasien <span class="text-red-500">*</span></label>
                        <select name="patient_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                            <option value="">-- Pilih Pasien --</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient['id'] }}" @selected(old('patient_id') == $patient['id'])>{{ $patient['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Dokter Pemeriksa <span class="text-red-500">*</span></label>
                        <select name="doctor_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                            <option value="">-- Pilih Dokter --</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor['id'] }}" @selected(old('doctor_id') == $doctor['id'])>{{ $doctor['name'] }} ({{ $doctor['spesialis'] }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Poli Kunjungan <span class="text-red-500">*</span></label>
                        <select name="clinic_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                            <option value="">-- Pilih Poli --</option>
                            @foreach($clinics as $clinic)
                                <option value="{{ $clinic->id }}" @selected(old('clinic_id') == $clinic->id)>{{ $clinic->nama_poli }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Pemeriksaan <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_periksa" required value="{{ old('tanggal_periksa') ?? \Carbon\Carbon::now()->format('Y-m-d') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Keluhan Utama <span class="text-red-500">*</span></label>
                        <input type="text" name="keluhan" placeholder="Keluhan yang dirasakan pasien" required value="{{ old('keluhan') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                </div>

                <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">Catatan Medis</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Diagnosa (ICD-10) <span class="text-red-500">*</span></label>
                        <textarea name="diagnosa" rows="2" placeholder="Masukkan Diagnosa Akhir" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">{{ old('diagnosa') }}</textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tindakan / Prosedur</label>
                        <textarea name="tindakan" rows="2" placeholder="Tindakan yang dilakukan (misal: suntik, jahit, rujukan)"
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">{{ old('tindakan') }}</textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan Dokter Tambahan</label>
                        <textarea name="catatan_dokter" rows="3" placeholder="Instruksi atau catatan penting lainnya"
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">{{ old('catatan_dokter') }}</textarea>
                    </div>
                </div>


                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModal('modalAddRM')"
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-semibold">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-6 py-2 bg-primary text-white rounded-xl hover:bg-secondary transition-colors font-semibold shadow-md hover:shadow-lg">
                        <i class="bi bi-check-lg mr-2"></i> Simpan Rekam Medis
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Rekam Medis (Mirip Modal Edit Obat) --}}
    <div id="modalEditRM" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 rounded-t-2xl">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="bi bi-pencil-square text-yellow-600 mr-3"></i> Edit Data Rekam Medis
                    </h2>
                    <button type="button" onclick="closeModal('modalEditRM')" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="bi bi-x-lg text-2xl"></i>
                    </button>
                </div>
            </div>
            
            <form id="formEditRM" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT') 
                
                <input type="hidden" id="edit_id" name="id"> 

                <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">Informasi Kunjungan</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Pasien <span class="text-red-500">*</span></label>
                        <select id="edit_patient_id" name="patient_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                            <option value="">-- Pilih Pasien --</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient['id'] }}">{{ $patient['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Dokter Pemeriksa <span class="text-red-500">*</span></label>
                        <select id="edit_doctor_id" name="doctor_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                            <option value="">-- Pilih Dokter --</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor['id'] }}">{{ $doctor['name'] }} ({{ $doctor['spesialis'] }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Poli Kunjungan <span class="text-red-500">*</span></label>
                        <select id="edit_clinic_id" name="clinic_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                            <option value="">-- Pilih Poli --</option>
                            @foreach($clinics as $clinic)
                                <option value="{{ $clinic->id }}">{{ $clinic->nama_poli }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Pemeriksaan <span class="text-red-500">*</span></label>
                        <input type="date" id="edit_tanggal_periksa" name="tanggal_periksa" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Keluhan Utama <span class="text-red-500">*</span></label>
                        <input type="text" id="edit_keluhan" name="keluhan" placeholder="Keluhan yang dirasakan pasien" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                </div>

                <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">Catatan Medis</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Diagnosa (ICD-10) <span class="text-red-500">*</span></label>
                        <textarea id="edit_diagnosa" name="diagnosa" rows="2" placeholder="Masukkan Diagnosa Akhir" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all"></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tindakan / Prosedur</label>
                        <textarea id="edit_tindakan" name="tindakan" rows="2" placeholder="Tindakan yang dilakukan (misal: suntik, jahit, rujukan)"
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all"></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan Dokter Tambahan</label>
                        <textarea id="edit_catatan_dokter" name="catatan_dokter" rows="3" placeholder="Instruksi atau catatan penting lainnya"
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all"></textarea>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModal('modalEditRM')"
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-semibold">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-6 py-2 bg-yellow-600 text-white rounded-xl hover:bg-yellow-700 transition-colors font-semibold shadow-md hover:shadow-lg">
                        <i class="bi bi-check-lg mr-2"></i> Update Rekam Medis
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal View Detail Rekam Medis (Mirip Modal View Obat) --}}
    <div id="modalViewRM" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 rounded-t-2xl">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="bi bi-file-earmark-medical text-primary mr-3"></i> Detail Rekam Medis
                    </h2>
                    <button type="button" onclick="closeModal('modalViewRM')" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="bi bi-x-lg text-2xl"></i>
                    </button>
                </div>
            </div>
            
            <div class="p-6 space-y-6">
                
                <div class="bg-gradient-to-r from-primary/10 to-secondary/10 rounded-xl p-5">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Nomor Rekam Medis</p>
                            <p id="view_rm_id" class="text-2xl font-bold text-primary">-</p>
                        </div>
                        <span id="view_date" class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-xs font-semibold">
                            Tgl: -
                        </span>
                    </div>
                    <div class="mt-4">
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Pasien</p>
                        <p id="view_patient_name" class="text-xl font-bold text-gray-800">-</p>
                        <p id="view_clinic_name" class="text-sm text-gray-600">-</p>
                    </div>
                </div>

                <div class="border border-gray-200 rounded-xl p-4">
                    <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Keluhan Utama</p>
                    <p id="view_keluhan" class="text-sm text-gray-700 leading-relaxed italic">-</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="border border-gray-200 rounded-xl p-4">
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Dokter Pemeriksa</p>
                        <p id="view_doctor_name" class="text-lg font-bold text-gray-800">-</p>
                    </div>

                    <div class="border border-gray-200 rounded-xl p-4">
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Diagnosa (ICD-10)</p>
                        <p id="view_diagnosa" class="text-lg font-bold text-red-600">-</p>
                    </div>
                </div>
                
                <div class="border border-gray-200 rounded-xl p-4">
                    <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Tindakan / Prosedur</p>
                    <p id="view_tindakan" class="text-sm text-gray-700 leading-relaxed">-</p>
                </div>

                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                    <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Catatan Dokter</p>
                    <p id="view_catatan_dokter" class="text-sm text-gray-700 leading-relaxed">-</p>
                </div>
                
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModal('modalViewRM')"
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-semibold">
                        Tutup
                    </button>
                    <button type="button" id="btnEditFromView"
                        class="px-6 py-2 bg-yellow-600 text-white rounded-xl hover:bg-yellow-700 transition-colors font-semibold shadow-md hover:shadow-lg">
                        <i class="bi bi-pencil-square mr-2"></i> Edit Data
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus Rekam Medis (Mirip Modal Delete Obat) --}}
    <div id="modalDeleteRM" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
            <div class="p-6">
                <div class="flex items-center justify-center w-16 h-16 mx-auto bg-red-100 rounded-full mb-4">
                    <i class="bi bi-exclamation-triangle-fill text-red-600 text-3xl"></i>
                </div>
                
                <h3 class="text-xl font-bold text-gray-800 text-center mb-2">Konfirmasi Hapus</h3>
                <p class="text-gray-600 text-center mb-1">Apakah Anda yakin ingin menghapus Rekam Medis:</p>
                <p id="delete_rm_info" class="text-center font-bold text-gray-800 mb-4">-</p>
                <p class="text-sm text-red-600 text-center mb-6">Tindakan ini tidak dapat dibatalkan!</p>
                
                <form id="formDeleteRM" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <div class="flex space-x-3">
                        <button type="button" onclick="closeModal('modalDeleteRM')"
                            class="flex-1 px-6 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-semibold">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-6 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors font-semibold shadow-md hover:shadow-lg">
                            <i class="bi bi-trash mr-2"></i> Hapus Permanen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script JavaScript (Disesuaikan untuk Rekam Medis) --}}
    <script>
        let currentRMData = null;

        // FUNGSI UTILITY (Sama seperti di obat.blade.php)
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';

            // Logic untuk menampilkan modal Add jika ada error validasi
            @if ($errors->any() && old('patient_id'))
                if (modalId === 'modalAddRM') {
                    // Cukup buka modal, karena old() sudah mengisi data
                }
            @endif
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
            const form = modal.querySelector('form');
            if (form && modalId === 'modalAddRM') {
                 @if (!$errors->any())
                    form.reset();
                @endif
            }
        }
        
        function formatDateIndo(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            const options = { day: 'numeric', month: 'long', year: 'numeric' };
            return date.toLocaleDateString('id-ID', options);
        }

        function formatRMId(id) {
             return 'RM-' + id.toString().padStart(5, '0');
        }

        // FUNGSI MEMBACA DATA DARI ATTRIBUTE (RM Handlers)
        function viewRMFromData(buttonElement) {
            const dataString = buttonElement.getAttribute('data-record');
            const data = JSON.parse(dataString);
            viewRM(data);
        }
        
        function editRMFromData(buttonElement) {
            const dataString = buttonElement.getAttribute('data-record');
            const data = JSON.parse(dataString);
            editRM(data);
        }

        // 1. View Rekam Medis (Logika utama)
        function viewRM(data) {
            currentRMData = data;
            
            document.getElementById('view_rm_id').textContent = formatRMId(data.id);
            document.getElementById('view_patient_name').textContent = data.patient_name;
            document.getElementById('view_doctor_name').textContent = data.doctor_name;
            document.getElementById('view_clinic_name').textContent = 'Poli: ' + data.clinic_name;
            document.getElementById('view_date').textContent = 'Tgl: ' + formatDateIndo(data.tanggal_periksa);
            document.getElementById('view_keluhan').textContent = data.keluhan;
            document.getElementById('view_diagnosa').textContent = data.diagnosa;
            document.getElementById('view_tindakan').textContent = data.tindakan || 'Tidak ada tindakan.';
            document.getElementById('view_catatan_dokter').textContent = data.catatan_dokter || 'Tidak ada catatan tambahan.';
            
            document.getElementById('btnEditFromView').onclick = function() {
                closeModal('modalViewRM');
                editRM(currentRMData);
            };

            openModal('modalViewRM');
        }

        // 2. Edit Rekam Medis (Logika utama)
        function editRM(data) {
            currentRMData = data;
            
            const form = document.getElementById('formEditRM');
            // Ganti URL action sesuai route Rekam Medis
            form.action = "{{ url('admin/rekam_medis') }}/" + data.id; 
            
            document.getElementById('edit_id').value = data.id; 
            document.getElementById('edit_patient_id').value = data.patient_id;
            document.getElementById('edit_doctor_id').value = data.doctor_id;
            document.getElementById('edit_clinic_id').value = data.clinic_id;
            document.getElementById('edit_tanggal_periksa').value = data.tanggal_periksa;
            document.getElementById('edit_keluhan').value = data.keluhan;
            document.getElementById('edit_diagnosa').value = data.diagnosa;
            document.getElementById('edit_tindakan').value = data.tindakan;
            document.getElementById('edit_catatan_dokter').value = data.catatan_dokter;
            
            openModal('modalEditRM');
        }

        // 3. Delete Rekam Medis (Konfirmasi)
        function deleteRM(id, info) {
            document.getElementById('delete_rm_info').textContent = info;
            
            const form = document.getElementById('formDeleteRM');
            // Ganti URL action sesuai route Rekam Medis
            form.action = "{{ url('admin/rekam_medis') }}/" + id; 
            
            openModal('modalDeleteRM');
        }
        
        // Event listener saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            // Tutup modal ketika klik di luar modal
            document.querySelectorAll('[id^="modal"]').forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeModal(this.id);
                    }
                });
            });

            // Keyboard shortcut - ESC untuk menutup modal
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const openModals = document.querySelectorAll('[id^="modal"]:not(.hidden)');
                    openModals.forEach(modal => {
                        closeModal(modal.id);
                    });
                }
            });

            // LOGIC MENAMPILKAN MODAL TAMBAH JIKA ADA VALIDASI ERROR
            @if ($errors->any() && old('patient_id'))
                openModal('modalAddRM');
            @endif
        });
    </script>

@endsection