{{-- resources/views/admin/pasien.blade.php --}}

@extends('layouts.app')

@section('title', 'Manajemen Data Pasien')

@section('content')

    <div class="space-y-6">
        
        {{-- Header Halaman --}}
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <i class="bi bi-people-fill text-primary mr-3"></i> Manajemen Data Pasien
            </h1>
            
            {{-- Tombol Add (Memanggil Modal) --}}
            <button onclick="openModal('modalAddPasien')" class="bg-primary hover:bg-secondary text-white font-semibold py-2 px-5 rounded-xl transition-colors duration-300 flex items-center shadow-md hover:shadow-lg">
                <i class="bi bi-person-plus-fill mr-2"></i> Tambah Pasien Baru
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
                        <input type="text" placeholder="Cari berdasarkan Nama, NIK, atau BPJS..." 
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                            aria-label="Cari Pasien">
                        <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <select class="p-2 border border-gray-300 rounded-xl text-sm focus:ring-primary focus:border-primary transition-colors">
                        <option>Semua Jenis Kelamin</option>
                        <option>Laki-laki</option>
                        <option>Perempuan</option>
                    </select>

                    <select class="p-2 border border-gray-300 rounded-xl text-sm focus:ring-primary focus:border-primary transition-colors hidden sm:block">
                        <option>Semua Gol. Darah</option>
                        <option>A</option>
                        <option>B</option>
                        <option>AB</option>
                        <option>O</option>
                    </select>
                </div>
            </div>

            {{-- Tabel Data Pasien (Struktur mirip obat.blade.php) --}}
            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-20">NIK</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Pasien</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden sm:table-cell">Kontak (HP)</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Tgl. Lahir / JK</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden md:table-cell">BPJS</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        
                        @forelse($patients as $patient)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-primary">
                                {{ $patient->nik }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">{{ $patient->user->name ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $patient->user->email ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden sm:table-cell">
                                {{ $patient->no_hp ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                @php
                                    $genderClass = ($patient->jenis_kelamin == 'L') ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800';
                                @endphp
                                <span class="{{ $genderClass }} px-3 py-1 rounded-full text-xs font-semibold">
                                    {{ $patient->jenis_kelamin }} / {{ \Carbon\Carbon::parse($patient->tanggal_lahir)->isoFormat('D MMM YYYY') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden md:table-cell">
                                @php
                                    $hasBpjs = !empty($patient->no_bpjs);
                                    $bpjsNumber = $patient->no_bpjs ?? 'NON-BPJS';
                                @endphp
                                <span class="{{ $hasBpjs ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} text-xs font-medium px-2 py-1 rounded-full">
                                    {{ $bpjsNumber }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-center space-x-1.5">
                                    
                                    {{-- Data JSON tunggal Pasien (Sama seperti obat.blade.php) --}}
                                    @php
                                        $dataJson = [
                                            'id' => $patient->id,
                                            'user_id' => $patient->user_id,
                                            'name' => $patient->user->name ?? '',
                                            'email' => $patient->user->email ?? '',
                                            'nik' => $patient->nik,
                                            'no_bpjs' => $patient->no_bpjs,
                                            'alamat' => $patient->alamat,
                                            'tanggal_lahir' => $patient->tanggal_lahir,
                                            'jenis_kelamin' => $patient->jenis_kelamin,
                                            'gol_darah' => $patient->gol_darah,
                                            'no_hp' => $patient->no_hp,
                                        ];
                                    @endphp

                                    {{-- Tombol View (Memanggil JS Pasien) --}}
                                    <button 
                                        type="button"
                                        data-patient='@json($dataJson)'
                                        onclick="viewPasienFromData(this)" 
                                        title="Lihat Detail Pasien" 
                                        class="text-primary hover:text-secondary p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-eye text-lg"></i>
                                    </button>
                                    
                                    {{-- Tombol Edit (Memanggil JS Pasien) --}}
                                    <button 
                                        type="button"
                                        data-patient='@json($dataJson)'
                                        onclick="editPasienFromData(this)" 
                                        title="Edit Data Pasien" 
                                        class="text-yellow-600 hover:text-yellow-800 p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-pencil-square text-lg"></i>
                                    </button>
                                    
                                    {{-- Tombol Delete (Memanggil JS Pasien) --}}
                                    <button onclick='deletePasien("{{ $patient->id }}", "{{ $patient->nik }} - {{ $patient->user->name ?? "N/A" }}")' title="Hapus Pasien" class="text-red-600 hover:text-red-800 p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-trash text-lg"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                Tidak ada data pasien yang ditemukan.
                            </td>
                        </tr>
                        @endforelse
                        
                    </tbody>
                </table>
            </div>
            
            {{-- Area Pagination --}}
            <div class="mt-6 flex justify-between items-center">
                <p class="text-sm text-gray-600 hidden sm:block">Menampilkan {{ $patients->firstItem() ?? 0 }} - {{ $patients->lastItem() ?? 0 }} dari {{ $patients->total() }} pasien</p>
                <div class="flex space-x-2 ml-auto">
                    {{ $patients->links('pagination::tailwind') }} 
                </div>
            </div>

        </div>

    </div>

    {{-- Modal Tambah Pasien Baru (Mirip Modal Add Obat) --}}
    <div id="modalAddPasien" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 rounded-t-2xl">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="bi bi-person-plus-fill text-primary mr-3"></i> Tambah Pasien Baru
                    </h2>
                    <button type="button" onclick="closeModal('modalAddPasien')" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="bi bi-x-lg text-2xl"></i>
                    </button>
                </div>
            </div>
            
            <form id="formAddPasien" action="{{ route('admin.pasien.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" placeholder="Nama Pasien" required value="{{ old('name') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email (Login) <span class="text-red-500">*</span></label>
                        <input type="email" name="email" placeholder="email@contoh.com" required value="{{ old('email') }}"
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

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">NIK <span class="text-red-500">*</span></label>
                        <input type="text" name="nik" placeholder="Nomor Induk Kependudukan" required value="{{ old('nik') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">No. BPJS (Opsional)</label>
                        <input type="text" name="no_bpjs" placeholder="Nomor BPJS" value="{{ old('no_bpjs') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tgl. Lahir <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_lahir" required value="{{ old('tanggal_lahir') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select name="jenis_kelamin" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                            <option value="">Pilih J. Kelamin</option>
                            <option value="L" @selected(old('jenis_kelamin') == 'L')>Laki-laki</option>
                            <option value="P" @selected(old('jenis_kelamin') == 'P')>Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Gol. Darah (Opsional)</label>
                        <select name="gol_darah"
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                            <option value="">Pilih Gol. Darah</option>
                            <option value="A" @selected(old('gol_darah') == 'A')>A</option>
                            <option value="B" @selected(old('gol_darah') == 'B')>B</option>
                            <option value="AB" @selected(old('gol_darah') == 'AB')>AB</option>
                            <option value="O" @selected(old('gol_darah') == 'O')>O</option>
                        </select>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor HP (Opsional)</label>
                    <input type="text" name="no_hp" placeholder="Contoh: 0812xxxx" value="{{ old('no_hp') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap <span class="text-red-500">*</span></label>
                    <textarea name="alamat" rows="3" placeholder="Masukkan alamat lengkap" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">{{ old('alamat') }}</textarea>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModal('modalAddPasien')"
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-semibold">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-6 py-2 bg-primary text-white rounded-xl hover:bg-secondary transition-colors font-semibold shadow-md hover:shadow-lg">
                        <i class="bi bi-check-lg mr-2"></i> Simpan Data Pasien
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Pasien (Mirip Modal Edit Obat) --}}
    <div id="modalEditPasien" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 rounded-t-2xl">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="bi bi-pencil-square text-yellow-600 mr-3"></i> Edit Data Pasien
                    </h2>
                    <button type="button" onclick="closeModal('modalEditPasien')" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="bi bi-x-lg text-2xl"></i>
                    </button>
                </div>
            </div>
            
            <form id="formEditPasien" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT') 
                
                <input type="hidden" id="edit_id" name="id"> 
                <input type="hidden" id="edit_user_id" name="user_id"> 

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
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
                        <label class="block text-sm font-semibold text-gray-700 mb-2">NIK <span class="text-red-500">*</span></label>
                        <input type="text" id="edit_nik" name="nik" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">No. BPJS (Opsional)</label>
                        <input type="text" id="edit_no_bpjs" name="no_bpjs" placeholder="Nomor BPJS"
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tgl. Lahir <span class="text-red-500">*</span></label>
                        <input type="date" id="edit_tanggal_lahir" name="tanggal_lahir" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select id="edit_jenis_kelamin" name="jenis_kelamin" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                            <option value="">Pilih J. Kelamin</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Gol. Darah (Opsional)</label>
                        <select id="edit_gol_darah" name="gol_darah"
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                            <option value="">Pilih Gol. Darah</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="AB">AB</option>
                            <option value="O">O</option>
                        </select>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor HP (Opsional)</label>
                    <input type="text" id="edit_no_hp" name="no_hp" placeholder="Contoh: 0812xxxx"
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap <span class="text-red-500">*</span></label>
                    <textarea id="edit_alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all"></textarea>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModal('modalEditPasien')"
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

    {{-- Modal View Detail Pasien (Mirip Modal View Obat) --}}
    <div id="modalViewPasien" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 rounded-t-2xl">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="bi bi-person-badge text-primary mr-3"></i> Detail Data Pasien
                    </h2>
                    <button type="button" onclick="closeModal('modalViewPasien')" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="bi bi-x-lg text-2xl"></i>
                    </button>
                </div>
            </div>
            
            <div class="p-6 space-y-6">
                <div class="bg-gradient-to-r from-primary/10 to-secondary/10 rounded-xl p-5">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold mb-1">NIK (Nomor Identitas)</p>
                            <p id="view_nik" class="text-2xl font-bold text-primary">-</p>
                        </div>
                        <span id="view_bpjs_status" class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-semibold">
                            NON-BPJS
                        </span>
                    </div>
                    <div class="mt-4">
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Nama Pasien</p>
                        <p id="view_name" class="text-xl font-bold text-gray-800">-</p>
                        <p id="view_email" class="text-sm text-gray-600">-</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="border border-gray-200 rounded-xl p-4">
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Nomor BPJS</p>
                        <p id="view_no_bpjs" class="text-lg font-bold text-gray-800">-</p>
                    </div>

                    <div class="border border-gray-200 rounded-xl p-4">
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Kontak (HP)</p>
                        <p id="view_no_hp" class="text-lg font-bold text-gray-800">-</p>
                    </div>

                    <div class="border border-gray-200 rounded-xl p-4">
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Tanggal Lahir</p>
                        <p id="view_tanggal_lahir" class="text-lg font-bold text-gray-800">-</p>
                    </div>

                    <div class="border border-gray-200 rounded-xl p-4">
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Jenis Kelamin / Gol. Darah</p>
                        <span id="view_jenis_kelamin" class="bg-pink-100 text-pink-800 text-sm font-medium px-3 py-1 rounded-full inline-block">
                            -
                        </span>
                         / 
                        <span id="view_gol_darah" class="bg-yellow-100 text-yellow-800 text-sm font-medium px-3 py-1 rounded-full inline-block">
                            -
                        </span>
                    </div>
                </div>

                <div class="border border-gray-200 rounded-xl p-4">
                    <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Alamat Lengkap</p>
                    <p id="view_alamat" class="text-sm text-gray-700 leading-relaxed">-</p>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModal('modalViewPasien')"
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

    {{-- Modal Konfirmasi Hapus Pasien (Mirip Modal Delete Obat) --}}
    <div id="modalDeletePasien" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
            <div class="p-6">
                <div class="flex items-center justify-center w-16 h-16 mx-auto bg-red-100 rounded-full mb-4">
                    <i class="bi bi-exclamation-triangle-fill text-red-600 text-3xl"></i>
                </div>
                
                <h3 class="text-xl font-bold text-gray-800 text-center mb-2">Konfirmasi Hapus</h3>
                <p class="text-gray-600 text-center mb-1">Apakah Anda yakin ingin menghapus data pasien:</p>
                <p id="delete_pasien_info" class="text-center font-bold text-gray-800 mb-4">-</p>
                <p class="text-sm text-red-600 text-center mb-6">Tindakan ini juga akan menghapus akun login pasien!</p>
                
                <form id="formDeletePasien" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <div class="flex space-x-3">
                        <button type="button" onclick="closeModal('modalDeletePasien')"
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

    {{-- Script JavaScript (Disesuaikan untuk Pasien) --}}
    <script>
        let currentPasienData = null;

        // FUNGSI UTILITY (Diambil dari obat.blade.php)
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';

            // Logic untuk menampilkan modal Add jika ada error validasi
            @if ($errors->any() && old('nik'))
                if (modalId === 'modalAddPasien') {
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
            if (form && modalId === 'modalAddPasien') {
                 // Hanya reset form jika tidak ada validasi error
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

        // FUNGSI MEMBACA DATA DARI ATTRIBUTE (Pasien Handlers - Diambil dari obat.blade.php logic)
        function viewPasienFromData(buttonElement) {
            const dataString = buttonElement.getAttribute('data-patient');
            const data = JSON.parse(dataString);
            viewPasien(data);
        }
        
        function editPasienFromData(buttonElement) {
            const dataString = buttonElement.getAttribute('data-patient');
            const data = JSON.parse(dataString);
            editPasien(data);
        }

        // 1. View Pasien (Logika utama - Disesuaikan dengan data Pasien)
        function viewPasien(data) {
            currentPasienData = data;
            
            document.getElementById('view_nik').textContent = data.nik;
            document.getElementById('view_name').textContent = data.name;
            document.getElementById('view_email').textContent = 'Email: ' + data.email;
            document.getElementById('view_no_bpjs').textContent = data.no_bpjs || 'Tidak Terdaftar BPJS';
            document.getElementById('view_no_hp').textContent = data.no_hp || 'Tidak Ada Data';
            document.getElementById('view_tanggal_lahir').textContent = formatDateIndo(data.tanggal_lahir);
            document.getElementById('view_alamat').textContent = data.alamat || 'Tidak Ada Data';
            
            // Gender status
            const jkStatus = document.getElementById('view_jenis_kelamin');
            const jkClass = (data.jenis_kelamin === 'L') ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800';
            jkStatus.className = jkClass + ' text-sm font-medium px-3 py-1 rounded-full inline-block';
            jkStatus.textContent = (data.jenis_kelamin === 'L') ? 'Laki-laki' : 'Perempuan';

            // Golongan Darah
            const gdStatus = document.getElementById('view_gol_darah');
            gdStatus.textContent = data.gol_darah || '-';

            // BPJS Status
            const bpjsStatus = document.getElementById('view_bpjs_status');
            const hasBpjs = !!data.no_bpjs;
            const bpjsClass = hasBpjs ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
            const bpjsText = hasBpjs ? 'TERDAFTAR BPJS' : 'NON-BPJS';
            bpjsStatus.className = bpjsClass + ' px-3 py-1 rounded-full text-xs font-semibold';
            bpjsStatus.textContent = bpjsText;
            
            document.getElementById('btnEditFromView').onclick = function() {
                closeModal('modalViewPasien');
                editPasien(currentPasienData);
            };

            openModal('modalViewPasien');
        }

        // 2. Edit Pasien (Logika utama - Disesuaikan dengan data Pasien)
        function editPasien(data) {
            currentPasienData = data;
            
            const form = document.getElementById('formEditPasien');
            // FIX FINAL: Menggunakan URI Statis sesuai route Pasien
            form.action = "{{ url('admin/pasien') }}/" + data.id; 
            
            document.getElementById('edit_id').value = data.id; 
            document.getElementById('edit_user_id').value = data.user_id;
            document.getElementById('edit_name').value = data.name;
            document.getElementById('edit_email').value = data.email;
            document.getElementById('edit_nik').value = data.nik;
            document.getElementById('edit_no_bpjs').value = data.no_bpjs;
            document.getElementById('edit_tanggal_lahir').value = data.tanggal_lahir;
            document.getElementById('edit_jenis_kelamin').value = data.jenis_kelamin;
            document.getElementById('edit_gol_darah').value = data.gol_darah;
            document.getElementById('edit_no_hp').value = data.no_hp;
            document.getElementById('edit_alamat').value = data.alamat;
            
            openModal('modalEditPasien');
        }

        // 3. Delete Pasien (Konfirmasi - Disesuaikan dengan data Pasien)
        function deletePasien(id, info) {
            document.getElementById('delete_pasien_info').textContent = info;
            
            const form = document.getElementById('formDeletePasien');
            // FIX FINAL: Menggunakan URI Statis sesuai route Pasien
            form.action = "{{ url('admin/pasien') }}/" + id; 
            
            openModal('modalDeletePasien');
        }
        
        // Event listener saat halaman dimuat (Sama)
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
            @if ($errors->any() && old('nik'))
                openModal('modalAddPasien');
            @endif
        });
    </script>

@endsection