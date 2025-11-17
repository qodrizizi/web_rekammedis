{{-- resources/views/admin/roles.blade.php --}}

@extends('layouts.app')

@section('title', 'Manajemen Hak Akses')

@section('content')

    <div class="space-y-6">
        
        {{-- Header Halaman --}}
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <i class="bi bi-person-lock text-primary mr-3"></i> Manajemen Hak Akses (Roles)
            </h1>
            
            <button onclick="openModal('modalAddRole')" class="bg-primary hover:bg-secondary text-white font-semibold py-2 px-5 rounded-xl transition-colors duration-300 flex items-center shadow-md hover:shadow-lg">
                <i class="bi bi-plus-lg mr-2"></i> Tambah Hak Akses Baru
            </button>
        </div>

        {{-- Notifikasi Sukses & Error --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative shadow-md" role="alert">
                <strong class="font-bold">Berhasil!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative shadow-md" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
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
                        <input type="text" placeholder="Cari berdasarkan Nama Hak Akses..." 
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                            aria-label="Cari Hak Akses">
                        <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600 hidden sm:block">Total data: {{ $roles->total() }}</span>
                </div>
            </div>

            {{-- Tabel Data Hak Akses --}}
            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-12">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Hak Akses</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden sm:table-cell">Deskripsi</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-32">Dibuat Pada</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        
                        @forelse($roles as $role)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-primary">
                                #{{ $role->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">
                                    {{ $role->role_name }} 
                                    @if($role->id <= 4)
                                        <i class="bi bi-key-fill text-yellow-500 text-xs ml-1" title="Sistem Role"></i>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-normal text-sm text-gray-700 hidden sm:table-cell line-clamp-2">
                                {{ $role->description ?? 'Tidak ada deskripsi.' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-gray-500">
                                {{ \Carbon\Carbon::parse($role->created_at)->isoFormat('D MMM YYYY') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-center space-x-1.5">
                                    
                                    {{-- Data JSON tunggal Role --}}
                                    @php
                                        $dataJson = [
                                            'id' => $role->id,
                                            'role_name' => $role->role_name,
                                            'description' => $role->description,
                                            'created_at' => $role->created_at,
                                            'updated_at' => $role->updated_at,
                                        ];
                                    @endphp

                                    {{-- Tombol View (Memanggil Modal) --}}
                                    <button 
                                        type="button"
                                        data-role='@json($dataJson)'
                                        onclick="viewRoleFromData(this)" 
                                        title="Lihat Detail Role" 
                                        class="text-primary hover:text-secondary p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-info-circle text-lg"></i>
                                    </button>
                                    
                                    {{-- Tombol Edit (Memanggil Modal) --}}
                                    <button 
                                        type="button"
                                        data-role='@json($dataJson)'
                                        onclick="editRoleFromData(this)" 
                                        title="Edit Data Role" 
                                        class="text-yellow-600 hover:text-yellow-800 p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-pencil-square text-lg"></i>
                                    </button>
                                    
                                    {{-- Tombol Delete (Memanggil Modal) --}}
                                    <button onclick='deleteRole("{{ $role->id }}", "{{ $role->role_name }}")' title="Hapus Role" class="text-red-600 hover:text-red-800 p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-trash text-lg"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                Tidak ada data hak akses yang ditemukan.
                            </td>
                        </tr>
                        @endforelse
                        
                    </tbody>
                </table>
            </div>
            
            {{-- Area Pagination --}}
            <div class="mt-6 flex justify-between items-center">
                <p class="text-sm text-gray-600 hidden sm:block">Menampilkan {{ $roles->firstItem() ?? 0 }} - {{ $roles->lastItem() ?? 0 }} dari {{ $roles->total() }} hak akses</p>
                <div class="flex space-x-2 ml-auto">
                    {{ $roles->links('pagination::tailwind') }} 
                </div>
            </div>

        </div>

    </div>

    {{-- MODALS SECTION --}}

    {{-- Modal Tambah Hak Akses Baru --}}
    <div id="modalAddRole" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 rounded-t-2xl">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="bi bi-plus-circle-fill text-primary mr-3"></i> Tambah Hak Akses Baru
                    </h2>
                    <button type="button" onclick="closeModal('modalAddRole')" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="bi bi-x-lg text-2xl"></i>
                    </button>
                </div>
            </div>
            
            <form id="formAddRole" action="{{ route('admin.roles.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Hak Akses <span class="text-red-500">*</span></label>
                    <input type="text" name="role_name" placeholder="Contoh: Petugas Laboratorium" required
                        value="{{ old('role_name') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi / Keterangan</label>
                    <textarea name="description" rows="4" placeholder="Deskripsi tugas dan hak akses role ini"
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">{{ old('description') }}</textarea>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModal('modalAddRole')"
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-semibold">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-6 py-2 bg-primary text-white rounded-xl hover:bg-secondary transition-colors font-semibold shadow-md hover:shadow-lg">
                        <i class="bi bi-check-lg mr-2"></i> Simpan Hak Akses
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Hak Akses --}}
    <div id="modalEditRole" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 rounded-t-2xl">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="bi bi-pencil-square text-yellow-600 mr-3"></i> Edit Data Hak Akses
                    </h2>
                    <button type="button" onclick="closeModal('modalEditRole')" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="bi bi-x-lg text-2xl"></i>
                    </button>
                </div>
            </div>
            
            <form id="formEditRole" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT') 
                
                <input type="hidden" id="edit_id" name="id"> 

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Hak Akses <span class="text-red-500">*</span></label>
                    <input type="text" id="edit_role_name" name="role_name" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi / Keterangan</label>
                    <textarea id="edit_description" name="description" rows="4" placeholder="Deskripsi tugas dan hak akses role ini"
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all"></textarea>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModal('modalEditRole')"
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-semibold">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-6 py-2 bg-yellow-600 text-white rounded-xl hover:bg-yellow-700 transition-colors font-semibold shadow-md hover:shadow-lg">
                        <i class="bi bi-check-lg mr-2"></i> Update Hak Akses
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal View Detail Hak Akses --}}
    <div id="modalViewRole" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 rounded-t-2xl">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="bi bi-info-circle-fill text-primary mr-3"></i> Detail Hak Akses
                    </h2>
                    <button type="button" onclick="closeModal('modalViewRole')" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="bi bi-x-lg text-2xl"></i>
                    </button>
                </div>
            </div>
            
            <div class="p-6 space-y-6">
                
                <div class="bg-gradient-to-r from-primary/10 to-secondary/10 rounded-xl p-5">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold mb-1">ID Hak Akses</p>
                            <p id="view_id" class="text-2xl font-bold text-primary">-</p>
                        </div>
                        <span id="view_created_at" class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-xs font-semibold">
                            Dibuat: -
                        </span>
                    </div>
                    <div class="mt-4">
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Nama Hak Akses</p>
                        <p id="view_role_name" class="text-xl font-bold text-gray-800">-</p>
                    </div>
                </div>

                <div class="border border-gray-200 rounded-xl p-4">
                    <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Deskripsi / Keterangan</p>
                    <p id="view_description" class="text-sm text-gray-700 leading-relaxed">-</p>
                </div>
                
                <div class="border border-gray-200 rounded-xl p-4">
                    <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Update Terakhir</p>
                    <p id="view_updated_at" class="text-sm text-gray-700 leading-relaxed">-</p>
                </div>
                
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModal('modalViewRole')"
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

    {{-- Modal Konfirmasi Hapus Hak Akses --}}
    <div id="modalDeleteRole" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
            <div class="p-6">
                <div class="flex items-center justify-center w-16 h-16 mx-auto bg-red-100 rounded-full mb-4">
                    <i class="bi bi-exclamation-triangle-fill text-red-600 text-3xl"></i>
                </div>
                
                <h3 class="text-xl font-bold text-gray-800 text-center mb-2">Konfirmasi Hapus</h3>
                <p class="text-gray-600 text-center mb-1">Anda akan menghapus Hak Akses:</p>
                <p id="delete_role_info" class="text-center font-bold text-gray-800 mb-4">-</p>
                <p class="text-sm text-red-600 text-center mb-6">Penghapusan ini akan gagal jika ada pengguna yang terikat pada Hak Akses ini!</p>
                
                <form id="formDeleteRole" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <div class="flex space-x-3">
                        <button type="button" onclick="closeModal('modalDeleteRole')"
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

    {{-- Script JavaScript (Disesuaikan untuk Roles) --}}
    <script>
        let currentRoleData = null;

        // --- FUNGSI UTILITY ---
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';

            @if ($errors->any() && old('role_name'))
                if (modalId === 'modalAddRole') {
                    // Cukup buka modal jika ada error validasi
                }
            @endif
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
            const form = modal.querySelector('form');
            if (form && modalId === 'modalAddRole') {
                 @if (!$errors->any())
                    form.reset();
                @endif
            }
        }
        
        function formatDateTimeIndo(dateTimeString) {
            if (!dateTimeString) return '-';
            const date = new Date(dateTimeString);
            const options = { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' };
            return date.toLocaleDateString('id-ID', options);
        }
        
        // --- HANDLERS DARI TOMBOL ---
        function viewRoleFromData(buttonElement) {
            const dataString = buttonElement.getAttribute('data-role');
            const data = JSON.parse(dataString);
            viewRole(data);
        }
        
        function editRoleFromData(buttonElement) {
            const dataString = buttonElement.getAttribute('data-role');
            const data = JSON.parse(dataString);
            editRole(data);
        }

        // 1. View Role (Logika utama)
        function viewRole(data) {
            currentRoleData = data;
            
            document.getElementById('view_id').textContent = '#' + data.id;
            document.getElementById('view_role_name').textContent = data.role_name;
            document.getElementById('view_description').textContent = data.description || 'Tidak ada deskripsi.';
            
            document.getElementById('view_created_at').textContent = 'Dibuat: ' + formatDateTimeIndo(data.created_at);
            document.getElementById('view_updated_at').textContent = formatDateTimeIndo(data.updated_at);

            document.getElementById('btnEditFromView').onclick = function() {
                closeModal('modalViewRole');
                editRole(currentRoleData);
            };

            openModal('modalViewRole');
        }

        // 2. Edit Role (Logika utama)
        function editRole(data) {
            currentRoleData = data;
            
            const form = document.getElementById('formEditRole');
            // Ganti URL action sesuai route Roles
            form.action = "{{ url('admin/roles') }}/" + data.id; 
            
            document.getElementById('edit_id').value = data.id; 
            document.getElementById('edit_role_name').value = data.role_name;
            document.getElementById('edit_description').value = data.description;
            
            openModal('modalEditRole');
        }

        // 3. Delete Role (Konfirmasi)
        function deleteRole(id, info) {
            document.getElementById('delete_role_info').textContent = info;
            
            const form = document.getElementById('formDeleteRole');
            // Ganti URL action sesuai route Roles
            form.action = "{{ url('admin/roles') }}/" + id; 
            
            openModal('modalDeleteRole');
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
            @if ($errors->any() && old('role_name'))
                openModal('modalAddRole');
            @endif
        });
    </script>

@endsection