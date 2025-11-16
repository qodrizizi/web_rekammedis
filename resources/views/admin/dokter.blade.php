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

        {{-- Notifikasi Sukses & Error (Sama dengan obat.blade.php) --}}
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

        {{-- Kontainer Utama Tabel dan Filter --}}
        <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-100">
            
            {{-- Area Filter dan Pencarian --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0">
                <div class="w-full md:w-1/3">
                    <div class="relative">
                        <input type="text" placeholder="Cari berdasarkan Nama, NIP, atau Spesialisasi..." 
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                            aria-label="Cari Dokter">
                        <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600 hidden sm:block">Filter Spesialisasi:</span>
                    <select class="p-2 border border-gray-300 rounded-xl text-sm focus:ring-primary focus:border-primary transition-colors">
                        <option>Semua Spesialisasi</option>
                        <option>Umum</option>
                        <option>Gigi</option>
                        <option>Anak</option>
                        {{-- Tambahkan loop untuk spesialisasi unik dari database --}}
                    </select>
                </div>
            </div>

            {{-- Tabel Data Dokter --}}
            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-20">NIP</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Dokter</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden sm:table-cell">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden lg:table-cell">Spesialisasi</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jadwal Praktik</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        
                        @forelse($doctors as $doctor)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-primary font-mono">
                                {{ $doctor->nip }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">{{ $doctor->user->name ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500 capitalize">{{ $doctor->spesialis }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">
                                <div class="text-sm text-gray-700">{{ $doctor->user->email ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap hidden lg:table-cell">
                                <span class="bg-primary/10 text-primary text-xs font-medium px-2 py-1 rounded-full">
                                    {{ $doctor->spesialis }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-normal text-sm text-gray-700">
                                <p class="line-clamp-2">{{ $doctor->jadwal_praktek }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-center space-x-1.5">
                                    
                                    {{-- Data JSON tunggal Dokter --}}
                                    @php
                                        $dataJson = [
                                            'id' => $doctor->id,
                                            'user_id' => $doctor->user_id,
                                            'name' => $doctor->user->name ?? '',
                                            'email' => $doctor->user->email ?? '',
                                            'nip' => $doctor->nip,
                                            'spesialis' => $doctor->spesialis,
                                            'jadwal' => $doctor->jadwal_praktek,
                                        ];
                                    @endphp

                                    {{-- Tombol View (Memanggil Modal) --}}
                                    <button 
                                        type="button"
                                        data-doctor='@json($dataJson)'
                                        onclick="viewDokterFromData(this)" 
                                        title="Lihat Detail & Jadwal" 
                                        class="text-primary hover:text-secondary p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-person-vcard text-lg"></i>
                                    </button>
                                    
                                    {{-- Tombol Edit (Memanggil Modal) --}}
                                    <button 
                                        type="button"
                                        data-doctor='@json($dataJson)'
                                        onclick="editDokterFromData(this)" 
                                        title="Edit Data Dokter" 
                                        class="text-yellow-600 hover:text-yellow-800 p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-pencil-square text-lg"></i>
                                    </button>
                                    
                                    {{-- Tombol Delete (Memanggil Modal) --}}
                                    <button onclick='deleteDokter("{{ $doctor->id }}", "{{ $doctor->nip }} - {{ $doctor->user->name ?? "N/A" }}")' title="Hapus Dokter" class="text-red-600 hover:text-red-800 p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-trash text-lg"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                Tidak ada data dokter yang ditemukan.
                            </td>
                        </tr>
                        @endforelse
                        
                    </tbody>
                </table>
            </div>
            
            {{-- Area Pagination --}}
            <div class="mt-6 flex justify-between items-center">
                <p class="text-sm text-gray-600 hidden sm:block">Menampilkan {{ $doctors->firstItem() ?? 0 }} - {{ $doctors->lastItem() ?? 0 }} dari {{ $doctors->total() }} dokter</p>
                <div class="flex space-x-2 ml-auto">
                    {{ $doctors->links('pagination::tailwind') }} 
                </div>
            </div>

        </div>

    </div>

    {{-- Modal Tambah Dokter Baru (Mirip Modal Add Obat) --}}
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
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Spesialisasi <span class="text-red-500">*</span></label>
                        <select name="spesialis" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                            <option value="">Pilih Spesialisasi</option>
                            <option value="Umum" @selected(old('spesialis') == 'Umum')>Umum</option>
                            <option value="Gigi" @selected(old('spesialis') == 'Gigi')>Gigi</option>
                            <option value="Anak" @selected(old('spesialis') == 'Anak')>Anak</option>
                            <option value="THT" @selected(old('spesialis') == 'THT')>THT</option>
                            <option value="Mata" @selected(old('spesialis') == 'Mata')>Mata</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jadwal Praktik (Deskripsi) <span class="text-red-500">*</span></label>
                    <textarea name="jadwal_praktek" rows="3" placeholder="Contoh: Senin-Jumat, 08:00 - 14:00" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">{{ old('jadwal_praktek') }}</textarea>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModal('modalAddDokter')"
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-semibold">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-6 py-2 bg-primary text-white rounded-xl hover:bg-secondary transition-colors font-semibold shadow-md hover:shadow-lg">
                        <i class="bi bi-check-lg mr-2"></i> Simpan Data Dokter
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Dokter (Mirip Modal Edit Obat) --}}
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
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Spesialisasi <span class="text-red-500">*</span></label>
                        <select id="edit_spesialis" name="spesialis" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                            <option value="">Pilih Spesialisasi</option>
                            <option value="Umum">Umum</option>
                            <option value="Gigi">Gigi</option>
                            <option value="Anak">Anak</option>
                            <option value="THT">THT</option>
                            <option value="Mata">Mata</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jadwal Praktik (Deskripsi) <span class="text-red-500">*</span></label>
                    <textarea id="edit_jadwal" name="jadwal_praktek" rows="3" placeholder="Contoh: Senin-Jumat, 08:00 - 14:00" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all"></textarea>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModal('modalEditDokter')"
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-semibold">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-6 py-2 bg-yellow-600 text-white rounded-xl hover:bg-yellow-700 transition-colors font-semibold shadow-md hover:shadow-lg">
                        <i class="bi bi-check-lg mr-2"></i> Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal View Detail Dokter (Mirip Modal View Obat) --}}
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
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Spesialisasi</p>
                        <span id="view_spesialis" class="bg-indigo-100 text-indigo-800 text-sm font-medium px-3 py-1 rounded-full inline-block">
                            -
                        </span>
                    </div>

                    <div class="border border-gray-200 rounded-xl p-4">
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-2">ID Pengguna Sistem</p>
                        <p id="view_user_id" class="text-lg font-bold text-gray-800">-</p>
                    </div>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                    <div class="flex items-start">
                        <i class="bi bi-calendar-range text-yellow-600 text-2xl mr-3 mt-1"></i>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Jadwal Praktik</p>
                            <p id="view_jadwal" class="text-base font-bold text-gray-800 whitespace-pre-wrap">-</p>
                        </div>
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

    {{-- Modal Konfirmasi Hapus Dokter (Mirip Modal Delete Obat) --}}
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

    {{-- Script JavaScript (Disesuaikan untuk Dokter) --}}
    <script>
        let currentDokterData = null;

        // FUNGSI UTILITY (Sama seperti di obat.blade.php)
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';

            @if ($errors->any() && old('nip'))
                if (modalId === 'modalAddDokter') {
                    // Tampilkan modal Add jika ada error validasi
                }
            @endif
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
            const form = modal.querySelector('form');
            if (form && modalId === 'modalAddDokter') {
                @if (!$errors->any())
                    form.reset();
                @endif
            }
        }
        
        // FUNGSI MEMBACA DATA DARI ATTRIBUTE
        function viewDokterFromData(buttonElement) {
            const dataString = buttonElement.getAttribute('data-doctor');
            const data = JSON.parse(dataString);
            viewDokter(data);
        }
        
        function editDokterFromData(buttonElement) {
            const dataString = buttonElement.getAttribute('data-doctor');
            const data = JSON.parse(dataString);
            editDokter(data);
        }

        // 1. View Dokter (Logika utama)
        function viewDokter(data) {
            currentDokterData = data;
            
            document.getElementById('view_nip').textContent = data.nip;
            document.getElementById('view_name').textContent = data.name;
            document.getElementById('view_email').textContent = 'Email: ' + data.email;
            document.getElementById('view_spesialis').textContent = data.spesialis;
            document.getElementById('view_jadwal').textContent = data.jadwal;
            document.getElementById('view_user_id').textContent = data.user_id;

            // Status (Misalnya, selalu aktif karena ini manajemen dokter)
            document.getElementById('view_status').textContent = 'Dokter Aktif';
            document.getElementById('view_status').className = 'bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold';
            
            document.getElementById('btnEditFromView').onclick = function() {
                closeModal('modalViewDokter');
                editDokter(currentDokterData);
            };

            openModal('modalViewDokter');
        }

        // 2. Edit Dokter (Logika utama)
        function editDokter(data) {
            currentDokterData = data;
            
            const form = document.getElementById('formEditDokter');
            // FIX FINAL: Menggunakan URL Statis sesuai route Dokter
            form.action = "{{ url('admin/dokter') }}/" + data.id; 
            
            document.getElementById('edit_id').value = data.id; 
            document.getElementById('edit_user_id').value = data.user_id;
            document.getElementById('edit_name').value = data.name;
            document.getElementById('edit_email').value = data.email;
            document.getElementById('edit_nip').value = data.nip;
            document.getElementById('edit_spesialis').value = data.spesialis;
            document.getElementById('edit_jadwal').value = data.jadwal;
            
            openModal('modalEditDokter');
        }

        // 3. Delete Dokter (Konfirmasi)
        function deleteDokter(id, info) {
            document.getElementById('delete_dokter_info').textContent = info;
            
            const form = document.getElementById('formDeleteDokter');
            // FIX FINAL: Menggunakan URL Statis sesuai route Dokter
            form.action = "{{ url('admin/dokter') }}/" + id; 
            
            openModal('modalDeleteDokter');
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
            @if ($errors->any() && old('nip'))
                openModal('modalAddDokter');
            @endif
        });
    </script>

@endsection