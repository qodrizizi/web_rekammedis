{{-- resources/views/admin/dokter.blade.php --}}

@extends('layouts.app')

@section('title', 'Manajemen Data Dokter')

@section('content')

    <div class="space-y-6">
        
        {{-- Header Halaman --}}
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <i class="bi bi-person-badge-fill text-primary mr-3"></i> Manajemen Data Dokter
            </h1>
            
            <button onclick="openModal('modalAddDokter')" class="bg-primary hover:bg-secondary text-white font-semibold py-2 px-5 rounded-xl transition-colors duration-300 flex items-center shadow-md hover:shadow-lg">
                <i class="bi bi-person-plus-fill mr-2"></i> Tambah Data Dokter
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
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative shadow-md" role="alert">
                <strong class="font-bold">Gagal!</strong>
                <span class="block sm:inline">Terdapat kesalahan saat pengiriman data.</span>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Kontainer Utama Tabel dan Filter --}}
        <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-100">
            
            {{-- Area Filter dan Pencarian --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0">
                <div class="w-full md:w-1/3">
                    <div class="relative">
                        <input type="text" placeholder="Cari berdasarkan Nama, NIP, atau Poli..." 
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                            aria-label="Cari Dokter">
                        <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600 hidden sm:block">Filter Poli:</span>
                    <select class="p-2 border border-gray-300 rounded-xl text-sm focus:ring-primary focus:border-primary transition-colors">
                        <option>Semua Poli</option>
                        @foreach ($clinics as $clinic)
                            <option value="{{ $clinic->id }}">{{ $clinic->nama_poli }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Tabel Data Dokter --}}
            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">NIP</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Dokter</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Poliklinik</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($doctors as $doctor)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $doctor->nip }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $doctor->user->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $doctor->user->email ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-primary">
                                    {{ $doctor->clinic->nama_poli ?? 'N/A' }}
                                </td>
                                
                                {{-- Kolom Aksi --}}
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex justify-center space-x-1.5">
                                        
                                        {{-- Persiapan Data JSON (FIX DI SINI) --}}
                                        @php
                                            $doctorData = [
                                                'id' => $doctor->id,
                                                'user_id' => $doctor->user_id,
                                                'name' => $doctor->user->name ?? '',
                                                'email' => $doctor->user->email ?? '',
                                                'nip' => $doctor->nip,
                                                'clinic_id' => $doctor->clinic_id,
                                                'spesialis' => $doctor->spesialis, 
                                                'poli_name' => $doctor->clinic->nama_poli ?? 'N/A', 
                                                'jadwal' => $doctor->jadwal_praktek ? $doctor->jadwal_praktek : '[]', 
                                            ];
                                            $dataJsonString = json_encode($doctorData, JSON_HEX_APOS | JSON_HEX_QUOT); 
                                        @endphp

                                        {{-- Tombol View --}}
                                        <button 
                                            type="button"
                                            data-doctor='{{ $dataJsonString }}' 
                                            onclick="viewDokterFromData(this)" 
                                            title="Lihat Detail & Jadwal" 
                                            class="text-primary hover:text-secondary p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                            <i class="bi bi-person-vcard text-lg"></i>
                                        </button>
                                        
                                        {{-- Tombol Edit --}}
                                        <button 
                                            type="button"
                                            data-doctor='{{ $dataJsonString }}' 
                                            onclick="editDokterFromData(this)" 
                                            title="Edit Data Dokter" 
                                            class="text-yellow-600 hover:text-yellow-800 p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                            <i class="bi bi-pencil-square text-lg"></i>
                                        </button>
                                        
                                        {{-- Tombol Delete --}}
                                        <button onclick='deleteDokter("{{ $doctor->id }}", "{{ $doctor->nip }} - {{ $doctor->user->name ?? "N/A" }}")' title="Hapus Dokter" class="text-red-600 hover:text-red-800 p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                            <i class="bi bi-trash text-lg"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500">Tidak ada data dokter ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination --}}
            <div class="px-6 py-4">
                {{ $doctors->links() }}
            </div>
        </div>
    </div>


    {{-- ==================================================== --}}
    {{-- MODAL TAMBAH DOKTER BARU --}}
    {{-- ==================================================== --}}
    <div id="modalAddDokter" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 rounded-t-2xl">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="bi bi-person-plus-fill text-primary mr-3"></i> Tambah Data Dokter
                    </h2>
                    <button type="button" onclick="closeModal('modalAddDokter')" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="bi bi-x-lg text-2xl"></i>
                    </button>
                </div>
            </div>
            
            <form id="formAddDokter" action="{{ route('admin.dokter.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Data User/Login --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap Dokter <span class="text-red-500">*</span></label>
                        <input type="text" name="name" placeholder="Contoh: Dr. Budi Santoso" required value="{{ old('name') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email (Login) <span class="text-red-500">*</span></label>
                        <input type="email" name="email" placeholder="email@puskesmas.id" required value="{{ old('email') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password" placeholder="Minimal 8 Karakter" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password_confirmation" placeholder="Ulangi Password" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>

                    {{-- Data Dokter --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">NIP (Nomor Induk Pegawai) <span class="text-red-500">*</span></label>
                        <input type="text" name="nip" placeholder="Contoh: 198001012009011001" required value="{{ old('nip') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Poli Tujuan <span class="text-red-500">*</span></label>
                        <select name="clinic_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                            <option value="">Pilih Poli</option>
                            @foreach ($clinics as $clinic)
                                <option value="{{ $clinic->id }}" @selected(old('clinic_id') == $clinic->id)>{{ $clinic->nama_poli }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jadwal Praktik Harian <span class="text-red-500">*</span></label>
                    <div class="overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 bg-gray-50">
                            <thead>
                                <tr>
                                    <th class="p-2 text-left text-xs font-bold text-gray-600 uppercase">Hari</th>
                                    <th class="p-2 text-left text-xs font-bold text-gray-600 uppercase">Jam Mulai</th>
                                    <th class="p-2 text-left text-xs font-bold text-gray-600 uppercase">Jam Selesai</th>
                                    <th class="p-2 text-center text-xs font-bold text-gray-600 uppercase">Hapus</th>
                                </tr>
                            </thead>
                            <tbody id="add_schedule_body">
                                {{-- Schedule rows will be appended here by JS --}}
                            </tbody>
                        </table>
                    </div>
                    <button type="button" onclick="addScheduleRow('add_schedule_body')" class="text-sm font-medium text-primary hover:underline flex items-center">
                        <i class="bi bi-plus-circle-fill mr-1"></i> Tambah Hari/Jadwal
                    </button>
                    {{-- Hidden input untuk menampung data JSON jadwal --}}
                    <input type="hidden" name="schedule_data" id="add_schedule_data" required>
                </div>


                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModal('modalAddDokter')"
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-semibold">
                        Batal
                    </button>
                    <button type="submit" onclick="wrapScheduleData(document.getElementById('formAddDokter'))"
                        class="px-6 py-2 bg-primary text-white rounded-xl hover:bg-secondary transition-colors font-semibold shadow-md hover:shadow-lg">
                        <i class="bi bi-check-lg mr-2"></i> Simpan Data Dokter
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ==================================================== --}}
    {{-- MODAL VIEW DOKTER --}}
    {{-- ==================================================== --}}
    <div id="modalViewDokter" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 rounded-t-2xl">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="bi bi-person-badge text-primary mr-3"></i> Detail Data Dokter
                    </h2>
                    <button type="button" onclick="closeModal('modalViewDokter')" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="bi bi-x-lg text-2xl"></i>
                    </button>
                </div>
            </div>
            
            <div class="p-6 space-y-6">
                <div class="bg-gradient-to-r from-primary/10 to-secondary/10 rounded-xl p-5">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold mb-1">NIP (Nomor Induk Pegawai)</p>
                            <p id="view_nip" class="text-2xl font-bold text-primary">-</p>
                        </div>
                        <span id="view_status" class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">
                            Dokter Aktif
                        </span>
                    </div>
                    <div class="mt-4">
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Nama Dokter</p>
                        <p id="view_name" class="text-xl font-bold text-gray-800">-</p>
                        <p id="view_email" class="text-sm text-gray-600">-</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="border border-gray-200 rounded-xl p-4">
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Poliklinik</p>
                        <span id="view_poli" class="bg-indigo-100 text-indigo-800 text-sm font-medium px-3 py-1 rounded-full inline-block">
                            -
                        </span>
                    </div>

                    <div class="border border-gray-200 rounded-xl p-4">
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Spesialisasi</p>
                        <p id="view_spesialis" class="text-lg font-bold text-gray-800">-</p>
                    </div>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                    <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Jadwal Praktik Harian</p>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-yellow-200">
                            <tbody id="view_schedule_body" class="divide-y divide-yellow-100">
                                {{-- Jadwal will be rendered here --}}
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModal('modalViewDokter')"
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


    {{-- ==================================================== --}}
    {{-- MODAL EDIT DOKTER --}}
    {{-- ==================================================== --}}
    <div id="modalEditDokter" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 rounded-t-2xl">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="bi bi-pencil-square text-yellow-600 mr-3"></i> Edit Data Dokter
                    </h2>
                    <button type="button" onclick="closeModal('modalEditDokter')" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="bi bi-x-lg text-2xl"></i>
                    </button>
                </div>
            </div>
            
            <form id="formEditDokter" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT') 
                
                <input type="hidden" id="edit_id" name="id"> 
                <input type="hidden" id="edit_user_id" name="user_id"> 

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap Dokter <span class="text-red-500">*</span></label>
                        <input type="text" id="edit_name" name="name" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email (Login) <span class="text-red-500">*</span></label>
                        <input type="email" id="edit_email" name="email" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Password Baru (Kosongkan jika tidak diganti)</label>
                        <input type="password" name="password" placeholder="Minimal 8 Karakter"
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" placeholder="Ulangi Password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">NIP <span class="text-red-500">*</span></label>
                        <input type="text" id="edit_nip" name="nip" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Poli Tujuan <span class="text-red-500">*</span></label>
                        <select id="edit_clinic_id" name="clinic_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                            <option value="">Pilih Poli</option>
                            @foreach ($clinics as $clinic)
                                <option value="{{ $clinic->id }}">{{ $clinic->nama_poli }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jadwal Praktik Harian <span class="text-red-500">*</span></label>
                    <div class="overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 bg-gray-50">
                            <thead>
                                <tr>
                                    <th class="p-2 text-left text-xs font-bold text-gray-600 uppercase">Hari</th>
                                    <th class="p-2 text-left text-xs font-bold text-gray-600 uppercase">Jam Mulai</th>
                                    <th class="p-2 text-left text-xs font-bold text-gray-600 uppercase">Jam Selesai</th>
                                    <th class="p-2 text-center text-xs font-bold text-gray-600 uppercase">Hapus</th>
                                </tr>
                            </thead>
                            <tbody id="edit_schedule_body">
                                {{-- Schedule rows will be appended here by JS --}}
                            </tbody>
                        </table>
                    </div>
                    <button type="button" onclick="addScheduleRow('edit_schedule_body')" class="text-sm font-medium text-primary hover:underline flex items-center">
                        <i class="bi bi-plus-circle-fill mr-1"></i> Tambah Hari/Jadwal
                    </button>
                    {{-- Hidden input untuk menampung data JSON jadwal --}}
                    <input type="hidden" name="schedule_data" id="edit_schedule_data" required>
                </div>


                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModal('modalEditDokter')"
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-semibold">
                        Batal
                    </button>
                    <button type="submit" onclick="wrapScheduleData(document.getElementById('formEditDokter'))"
                        class="px-6 py-2 bg-yellow-600 text-white rounded-xl hover:bg-yellow-700 transition-colors font-semibold shadow-md hover:shadow-lg">
                        <i class="bi bi-check-lg mr-2"></i> Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>


    {{-- ==================================================== --}}
    {{-- MODAL DELETE DOKTER --}}
    {{-- ==================================================== --}}
    <div id="modalDeleteDokter" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
            <div class="p-6">
                <div class="flex items-center justify-center w-16 h-16 mx-auto bg-red-100 rounded-full mb-4">
                    <i class="bi bi-exclamation-triangle-fill text-red-600 text-3xl"></i>
                </div>
                
                <h3 class="text-xl font-bold text-gray-800 text-center mb-2">Konfirmasi Hapus</h3>
                <p class="text-gray-600 text-center mb-1">Apakah Anda yakin ingin menghapus data dokter:</p>
                <p id="delete_dokter_info" class="text-center font-bold text-gray-800 mb-4">-</p>
                <p class="text-sm text-red-600 text-center mb-6">Tindakan ini juga akan menghapus akun login dokter!</p>
                
                <form id="formDeleteDokter" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <div class="flex space-x-3">
                        <button type="button" onclick="closeModal('modalDeleteDokter')"
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


    {{-- ==================================================== --}}
    {{-- SCRIPTS LOGIC --}}
    {{-- ==================================================== --}}
    <script>
        const daysOfWeek = ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"];

        // --- UTILITY MODAL ---
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.getElementById(id).classList.add('flex');
            document.body.style.overflow = 'hidden';
            // Khusus Add Modal, pastikan ada minimal 1 baris jadwal jika baru dibuka
            if (id === 'modalAddDokter') {
                 const container = document.getElementById('add_schedule_body');
                 if (container.children.length === 0) {
                     addScheduleRow('add_schedule_body');
                 }
            }
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.getElementById(id).classList.remove('flex');
            document.body.style.overflow = 'auto';
            
            // Clear schedule builder di modal yang ditutup
            const form = document.getElementById(id).querySelector('form');
            if (form) {
                // Jangan reset form jika ada error (supaya old data tetap ada)
                const isError = @json($errors->any());
                if (!isError || (id !== 'modalAddDokter' && id !== 'modalEditDokter')) {
                    form.reset();
                    // Clear schedule builder
                    const containerId = id.includes('Add') ? 'add_schedule_body' : 'edit_schedule_body';
                    const container = document.getElementById(containerId);
                    if (container) container.innerHTML = '';
                }
            }
        }

        // --- FUNGSI JADWAL DINAMIS ---
        
        function addScheduleRow(containerId, day = 'Senin', start = '08:00', end = '12:00') {
            const tbody = document.getElementById(containerId);
            const index = tbody.children.length;
            
            const tr = document.createElement('tr');
            tr.className = 'border-t border-gray-200';
            tr.innerHTML = `
                <td class="p-2">
                    <select name="day_${index}" class="form-select w-full border-gray-300 rounded-md p-1.5 text-sm" required>
                        ${daysOfWeek.map(d => `<option value="${d}" ${d === day ? 'selected' : ''}>${d}</option>`).join('')}
                    </select>
                </td>
                <td class="p-2">
                    <input type="time" name="start_${index}" value="${start}" class="form-input w-full border-gray-300 rounded-md p-1.5 text-sm" required>
                </td>
                <td class="p-2">
                    <input type="time" name="end_${index}" value="${end}" class="form-input w-full border-gray-300 rounded-md p-1.5 text-sm" required>
                </td>
                <td class="p-2 text-center">
                    <button type="button" onclick="removeScheduleRow(this, '${containerId}')" class="text-red-500 hover:text-red-700 p-1">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        }

        function removeScheduleRow(button, containerId) {
            button.closest('tr').remove();
            const tbody = document.getElementById(containerId);
            if (tbody.children.length === 0) {
                // Minimal harus ada 1 baris jadwal
                addScheduleRow(containerId);
            }
        }

        function redrawScheduleTable(containerId, schedule = []) {
            const tbody = document.getElementById(containerId);
            tbody.innerHTML = ''; // Bersihkan konten lama
            
            if (schedule.length > 0) {
                schedule.forEach(item => {
                    addScheduleRow(containerId, item.day, item.start, item.end);
                });
            } else {
                addScheduleRow(containerId);
            }
        }

        function wrapScheduleData(form) {
            const rows = form.querySelectorAll('tbody tr');
            const schedule = [];

            rows.forEach((row, index) => {
                const daySelect = row.querySelector(`select[name^="day_"]`);
                const startInput = row.querySelector(`input[name^="start_"]`);
                const endInput = row.querySelector(`input[name^="end_"]`);

                if (daySelect && startInput && endInput) {
                    const day = daySelect.value;
                    const start = startInput.value;
                    const end = endInput.value;
                    
                    if (day && start && end) {
                        schedule.push({ day, start, end });
                    }
                }
            });

            let hiddenInput = form.querySelector('input[name="schedule_data"]');
            if (!hiddenInput) {
                hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'schedule_data';
                form.appendChild(hiddenInput);
            }
            
            hiddenInput.value = JSON.stringify(schedule);
        }

        // --- LOGIKA UTAMA VIEW, EDIT, DELETE ---

        function viewDokterFromData(button) {
            const data = JSON.parse(button.getAttribute('data-doctor'));
            
            // 1. Isi Data Dasar
            document.getElementById('view_name').textContent = data.name;
            document.getElementById('view_email').textContent = data.email;
            document.getElementById('view_nip').textContent = data.nip;
            document.getElementById('view_poli').textContent = data.poli_name;
            document.getElementById('view_spesialis').textContent = data.spesialis;
            
            // 2. Isi Jadwal untuk View
            const scheduleBody = document.getElementById('view_schedule_body');
            scheduleBody.innerHTML = '';
            
            try {
                const schedule = JSON.parse(data.jadwal);
                if (schedule.length > 0) {
                    schedule.forEach(item => {
                        const tr = document.createElement('tr');
                        tr.className = 'border-b border-yellow-100';
                        tr.innerHTML = `
                            <td class="py-1 px-4 text-sm font-medium text-gray-700">${item.day}</td>
                            <td class="py-1 px-4 text-sm text-gray-600">${item.start} - ${item.end}</td>
                        `;
                        scheduleBody.appendChild(tr);
                    });
                } else {
                    scheduleBody.innerHTML = '<tr><td colspan="2" class="py-4 text-center text-gray-500">Jadwal belum diatur.</td></tr>';
                }
            } catch (e) {
                scheduleBody.innerHTML = '<tr><td colspan="2" class="py-4 text-center text-red-500">Format jadwal tidak valid.</td></tr>';
            }
            
            // Tombol Edit dari Modal View
            document.getElementById('btnEditFromView').onclick = function() {
                closeModal('modalViewDokter');
                editDokterFromData(button); // Panggil fungsi Edit
            };

            openModal('modalViewDokter');
        }

        function editDokterFromData(button) {
            const data = JSON.parse(button.getAttribute('data-doctor'));
            const form = document.getElementById('formEditDokter');
            
            // 1. Set Action URL
            form.action = "{{ url('admin/dokter') }}/" + data.id;
            
            // 2. Isi Data Dasar
            document.getElementById('edit_name').value = data.name;
            document.getElementById('edit_email').value = data.email;
            document.getElementById('edit_nip').value = data.nip;
            document.getElementById('edit_clinic_id').value = data.clinic_id;
            
            // 3. Isi Jadwal Praktek
            let schedule = [];
            try {
                schedule = JSON.parse(data.jadwal);
            } catch (e) {
                schedule = [];
            }
            
            // Gambar ulang tabel jadwal dengan data yang ada
            redrawScheduleTable('edit_schedule_body', schedule); 

            openModal('modalEditDokter');
        }

        function deleteDokter(id, info) {
            document.getElementById('delete_dokter_info').textContent = info;
            const form = document.getElementById('formDeleteDokter');
            form.action = "{{ url('admin/dokter') }}/" + id; 
            openModal('modalDeleteDokter');
        }
        
        // --- DOM LOAD / RE-LOAD DATA PADA ERROR ---
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi tabel jadwal untuk modal ADD (hanya untuk pertama kali)
            const addBody = document.getElementById('add_schedule_body');
            if (addBody && addBody.children.length === 0) {
                 addScheduleRow('add_schedule_body');
            }

            // LOGIC MENAMPILKAN MODAL TAMBAH JIKA ADA VALIDASI ERROR (SETELAH REDIRECT DENGAN OLD DATA)
            @if ($errors->any() && old('nip'))
                openModal('modalAddDokter');
                // Isi ulang jadwal jika ada old data
                @if (old('schedule_data'))
                    try {
                        const oldSchedule = JSON.parse('{!! old('schedule_data') !!}');
                        redrawScheduleTable('add_schedule_body', oldSchedule);
                    } catch(e) {
                        console.error('Failed to parse old schedule data:', e);
                        addScheduleRow('add_schedule_body'); 
                    }
                @endif
            @elseif ($errors->any() && old('_method') === 'PUT' && old('id'))
                // LOGIC UNTUK RE-LOAD MODAL EDIT JIKA VALIDASI GAGAL
                const editButton = document.querySelector(`[data-doctor*='"id":{{ old('id') }}"']`);
                if (editButton) {
                    editDokterFromData(editButton);
                }
            @endif
        });
    </script>
@endsection