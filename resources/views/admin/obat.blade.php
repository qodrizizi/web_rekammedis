@extends('layouts.app')

@section('title', 'Manajemen Data Obat & Farmasi')

@section('content')

    <div class="space-y-6">
        
        {{-- Header Halaman --}}
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <i class="bi bi-capsule-fill text-primary mr-3"></i> Manajemen Data Obat
            </h1>
            
            <button onclick="openModal('modalAddObat')" class="bg-primary hover:bg-secondary text-white font-semibold py-2 px-5 rounded-xl transition-colors duration-300 flex items-center shadow-md hover:shadow-lg">
                <i class="bi bi-plus-lg mr-2"></i> Tambah Obat Baru
            </button>
        </div>

        {{-- Notifikasi Sukses --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative shadow-md" role="alert">
                <strong class="font-bold">Berhasil!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        
        {{-- Notifikasi Error Validasi --}}
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
                        <input type="text" placeholder="Cari berdasarkan Nama, Kode, atau Kategori..." 
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                            aria-label="Cari Obat">
                        <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <select class="p-2 border border-gray-300 rounded-xl text-sm focus:ring-primary focus:border-primary transition-colors">
                        <option>Semua Kategori</option>
                        <option>Analgesik</option>
                        <option>Antibiotik</option>
                        <option>Obat Bebas</option>
                        <option>Suplemen</option>
                    </select>

                    <select class="p-2 border border-gray-300 rounded-xl text-sm focus:ring-primary focus:border-primary transition-colors hidden sm:block">
                        <option>Semua Stok</option>
                        <option>Stok Rendah (&lt; 100)</option>
                        <option>Stok Aman</option>
                    </select>
                </div>
            </div>

            {{-- Tabel Data Obat --}}
            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-12">Kode</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Obat (Satuan)</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden sm:table-cell">Kategori</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Stok</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider hidden md:table-cell">Harga Satuan</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        
                        {{-- Loop data Obat dari Controller ($medications) --}}
                        @forelse($medications as $medication)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-primary">
                                {{ $medication->kode_obat }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">{{ $medication->nama_obat }}</div>
                                <div class="text-xs text-gray-500">Satuan: {{ $medication->satuan }} | Exp: {{ \Carbon\Carbon::parse($medication->tanggal_kedaluwarsa)->isoFormat('D MMM YYYY') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden sm:table-cell">
                                <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2 py-1 rounded-full">
                                    {{ $medication->kategori ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                @php
                                    $stock = $medication->stok;
                                    $stockClass = 'bg-green-100 text-green-800';
                                    if ($stock < 100) {
                                        $stockClass = 'bg-red-100 text-red-800 font-bold';
                                    } elseif ($stock < 500) {
                                        $stockClass = 'bg-yellow-100 text-yellow-800';
                                    }
                                @endphp
                                <span class="{{ $stockClass }} px-3 py-1 rounded-full text-xs font-semibold">
                                    {{ number_format($stock, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-gray-900 hidden md:table-cell">
                                Rp{{ number_format($medication->harga, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-center space-x-1.5">
                                    
                                    {{-- Data JSON tunggal yang digunakan untuk View dan Edit --}}
                                    @php
                                        $dataJson = [
                                            'id' => $medication->id,
                                            'code' => $medication->kode_obat,
                                            'name' => $medication->nama_obat,
                                            'category' => $medication->kategori,
                                            'unit' => $medication->satuan,
                                            'stock' => $medication->stok,
                                            'unit_price' => $medication->harga,
                                            'exp' => $medication->tanggal_kedaluwarsa,
                                            'description' => $medication->deskripsi,
                                        ];
                                    @endphp

                                    {{-- Tombol View (Stabil: menggunakan data-* attribute) --}}
                                    <button 
                                        type="button"
                                        data-medication='@json($dataJson)'
                                        onclick="viewObatFromData(this)" 
                                        title="Lihat Detail Obat" 
                                        class="text-primary hover:text-secondary p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-box-seam text-lg"></i>
                                    </button>
                                    
                                    {{-- Tombol Edit (Stabil: menggunakan data-* attribute) --}}
                                    <button 
                                        type="button"
                                        data-medication='@json($dataJson)'
                                        onclick="editObatFromData(this)" 
                                        title="Edit Data Obat" 
                                        class="text-yellow-600 hover:text-yellow-800 p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-pencil-square text-lg"></i>
                                    </button>
                                    
                                    {{-- Tombol Delete --}}
                                    <button onclick='deleteObat("{{ $medication->id }}", "{{ $medication->kode_obat }} - {{ $medication->nama_obat }}")' title="Hapus Obat" class="text-red-600 hover:text-red-800 p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-trash text-lg"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                Tidak ada data obat yang ditemukan.
                            </td>
                        </tr>
                        @endforelse
                        
                    </tbody>
                </table>
            </div>
            
            {{-- Area Pagination (Placeholder) --}}
            <div class="mt-6 flex justify-between items-center">
                <p class="text-sm text-gray-600 hidden sm:block">Total data: {{ $medications->count() }} jenis obat</p>
                <div class="flex space-x-2 ml-auto">
                    {{-- Pagination buttons --}}
                </div>
            </div>

        </div>

    </div>

    {{-- Modal Tambah Obat Baru (Form Action diset ke admin.obat.store) --}}
    <div id="modalAddObat" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 rounded-t-2xl">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="bi bi-plus-circle-fill text-primary mr-3"></i> Tambah Obat Baru
                    </h2>
                    <button type="button" onclick="closeModal('modalAddObat')" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="bi bi-x-lg text-2xl"></i>
                    </button>
                </div>
            </div>
            
            <form id="formAddObat" action="{{ route('admin.obat.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kode Obat <span class="text-red-500">*</span></label>
                        <input type="text" name="code" placeholder="Contoh: OBT001" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Obat <span class="text-red-500">*</span></label>
                        <input type="text" name="name" placeholder="Contoh: Paracetamol 500mg" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                        <select name="category" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                            <option value="">Pilih Kategori</option>
                            <option value="Analgesik">Analgesik</option>
                            <option value="Antibiotik">Antibiotik</option>
                            <option value="Obat Bebas">Obat Bebas</option>
                            <option value="Suplemen">Suplemen</option>
                            <option value="Vitamin">Vitamin</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Satuan (Ex: Tablet, Botol) <span class="text-red-500">*</span></label>
                        <input type="text" name="unit" placeholder="Tablet" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Stok <span class="text-red-500">*</span></label>
                        <input type="number" name="stock" placeholder="0" min="0" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Harga Satuan (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="unit_price" placeholder="0" min="0" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Kedaluwarsa <span class="text-red-500">*</span></label>
                    <input type="date" name="exp_date" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan Tambahan</label>
                    <textarea name="description" rows="3" placeholder="Masukkan keterangan tambahan (misal: dosis, efek samping)"
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all"></textarea>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModal('modalAddObat')"
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-semibold">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-6 py-2 bg-primary text-white rounded-xl hover:bg-secondary transition-colors font-semibold shadow-md hover:shadow-lg">
                        <i class="bi bi-check-lg mr-2"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Obat --}}
    <div id="modalEditObat" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 rounded-t-2xl">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="bi bi-pencil-square text-yellow-600 mr-3"></i> Edit Data Obat
                    </h2>
                    <button type="button" onclick="closeModal('modalEditObat')" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="bi bi-x-lg text-2xl"></i>
                    </button>
                </div>
            </div>
            
            <form id="formEditObat" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT') 
                
                <input type="hidden" id="edit_id" name="id"> 

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kode Obat <span class="text-red-500">*</span></label>
                        <input type="text" id="edit_code" name="code" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all bg-gray-50" readonly>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Obat <span class="text-red-500">*</span></label>
                        <input type="text" id="edit_name" name="name" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                        <select id="edit_category" name="category" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                            <option value="">Pilih Kategori</option>
                            <option value="Analgesik">Analgesik</option>
                            <option value="Antibiotik">Antibiotik</option>
                            <option value="Obat Bebas">Obat Bebas</option>
                            <option value="Suplemen">Suplemen</option>
                            <option value="Vitamin">Vitamin</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Satuan (Ex: Tablet, Botol) <span class="text-red-500">*</span></label>
                        <input type="text" id="edit_unit" name="unit" placeholder="Tablet" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Stok <span class="text-red-500">*</span></label>
                        <input type="number" id="edit_stock" name="stock" min="0" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Harga Satuan (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" id="edit_unit_price" name="unit_price" min="0" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Kedaluwarsa <span class="text-red-500">*</span></label>
                    <input type="date" id="edit_exp_date" name="exp_date" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan Tambahan</label>
                    <textarea id="edit_description" name="description" rows="3" placeholder="Masukkan keterangan tambahan..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all"></textarea>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModal('modalEditObat')"
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

    {{-- Modal View Detail Obat --}}
    <div id="modalViewObat" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 rounded-t-2xl">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="bi bi-box-seam text-primary mr-3"></i> Detail Data Obat
                    </h2>
                    <button type="button" onclick="closeModal('modalViewObat')" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="bi bi-x-lg text-2xl"></i>
                    </button>
                </div>
            </div>
            
            <div class="p-6 space-y-6">
                <div class="bg-gradient-to-r from-primary/10 to-secondary/10 rounded-xl p-5">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Kode Obat</p>
                            <p id="view_code" class="text-2xl font-bold text-primary">-</p>
                        </div>
                        <span id="view_stock_status" class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">
                            Stok Aman
                        </span>
                    </div>
                    <div class="mt-4">
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Nama Obat</p>
                        <p id="view_name" class="text-xl font-bold text-gray-800">-</p>
                        <p id="view_unit" class="text-sm text-gray-600">-</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="border border-gray-200 rounded-xl p-4">
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Kategori</p>
                        <span id="view_category" class="bg-indigo-100 text-indigo-800 text-sm font-medium px-3 py-1 rounded-full inline-block">
                            -
                        </span>
                    </div>

                    <div class="border border-gray-200 rounded-xl p-4">
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Stok Tersedia</p>
                        <p id="view_stock" class="text-2xl font-bold text-gray-800">- <span class="text-sm font-normal text-gray-500">unit</span></p>
                    </div>

                    <div class="border border-gray-200 rounded-xl p-4">
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Harga Satuan</p>
                        <p id="view_unit_price" class="text-2xl font-bold text-gray-800">-</p>
                    </div>

                    <div class="border border-gray-200 rounded-xl p-4">
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Total Nilai Stok</p>
                        <p id="view_total_value" class="text-2xl font-bold text-primary">-</p>
                    </div>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                    <div class="flex items-start">
                        <i class="bi bi-calendar-event text-yellow-600 text-2xl mr-3 mt-1"></i>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Tanggal Kedaluwarsa</p>
                            <p id="view_exp_date" class="text-lg font-bold text-gray-800">-</p>
                            <p id="view_exp_remaining" class="text-sm text-gray-600 mt-1">-</p>
                        </div>
                    </div>
                </div>

                <div class="border border-gray-200 rounded-xl p-4">
                    <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Keterangan Tambahan</p>
                    <p id="view_description" class="text-sm text-gray-700 leading-relaxed">-</p>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModal('modalViewObat')"
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

    {{-- Modal Konfirmasi Hapus --}}
    <div id="modalDeleteObat" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
            <div class="p-6">
                <div class="flex items-center justify-center w-16 h-16 mx-auto bg-red-100 rounded-full mb-4">
                    <i class="bi bi-exclamation-triangle-fill text-red-600 text-3xl"></i>
                </div>
                
                <h3 class="text-xl font-bold text-gray-800 text-center mb-2">Konfirmasi Hapus</h3>
                <p class="text-gray-600 text-center mb-1">Apakah Anda yakin ingin menghapus obat:</p>
                <p id="delete_obat_info" class="text-center font-bold text-gray-800 mb-4">-</p>
                <p class="text-sm text-red-600 text-center mb-6">Tindakan ini tidak dapat dibatalkan!</p>
                
                <form id="formDeleteObat" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <div class="flex space-x-3">
                        <button type="button" onclick="closeModal('modalDeleteObat')"
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

    {{-- Script JavaScript --}}
    <script>
        let currentObatData = null;

        // FUNGSI UTILITY
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
            const form = modal.querySelector('form');
            if (form && modalId === 'modalAddObat') {
                form.reset();
            }
        }

        function formatRupiah(angka) {
            return 'Rp' + parseInt(angka).toLocaleString('id-ID');
        }

        function formatNumber(angka) {
            return parseInt(angka).toLocaleString('id-ID');
        }
        
        function calculateDateDifference(expDate) {
            const today = new Date();
            const exp = new Date(expDate);
            const diffTime = exp - today;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            if (diffDays < 0) {
                return '<span class="text-red-600">Sudah kedaluwarsa</span>';
            }
            if (diffDays < 90) { 
                return `<span class="text-orange-600">Kurang dari 3 bulan (${diffDays} hari)</span>`;
            }
            
            const years = Math.floor(diffDays / 365);
            const months = Math.floor((diffDays % 365) / 30);
            
            let result = 'Masih tersisa ';
            if (years > 0) result += years + ' tahun ';
            if (months > 0) result += months + ' bulan ';
            
            return result.trim();
        }

        function formatDateIndo(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            const options = { day: 'numeric', month: 'long', year: 'numeric' };
            return date.toLocaleDateString('id-ID', options);
        }

        // FUNGSI MEMBACA DATA DARI ATTRIBUTE (UNIFIED HANDLERS)
        function viewObatFromData(buttonElement) {
            const dataString = buttonElement.getAttribute('data-medication');
            const data = JSON.parse(dataString);
            viewObat(data);
        }
        
        function editObatFromData(buttonElement) {
            const dataString = buttonElement.getAttribute('data-medication');
            const data = JSON.parse(dataString);
            editObat(data);
        }

        // 1. View Obat (Logika utama)
        function viewObat(data) {
            currentObatData = data;
            
            document.getElementById('view_code').textContent = data.code;
            document.getElementById('view_name').textContent = data.name;
            document.getElementById('view_unit').textContent = 'Satuan: ' + data.unit;
            document.getElementById('view_category').textContent = data.category;
            document.getElementById('view_stock').innerHTML = formatNumber(data.stock) + ' <span class="text-sm font-normal text-gray-500">unit</span>';
            document.getElementById('view_unit_price').textContent = formatRupiah(data.unit_price);
            document.getElementById('view_total_value').textContent = formatRupiah(data.stock * data.unit_price);
            document.getElementById('view_exp_date').textContent = formatDateIndo(data.exp);
            document.getElementById('view_exp_remaining').innerHTML = calculateDateDifference(data.exp);
            document.getElementById('view_description').textContent = data.description || 'Tidak ada keterangan tambahan.';
            
            const stockStatus = document.getElementById('view_stock_status');
            let stockClass = 'bg-green-100 text-green-800';
            let stockText = 'Stok Aman';
            if (data.stock < 100) {
                stockClass = 'bg-red-100 text-red-800';
                stockText = 'Stok Rendah';
            } else if (data.stock < 500) {
                stockClass = 'bg-yellow-100 text-yellow-800';
                stockText = 'Stok Sedang';
            }
            stockStatus.className = stockClass + ' px-3 py-1 rounded-full text-xs font-semibold';
            stockStatus.textContent = stockText;
            
            document.getElementById('btnEditFromView').onclick = function() {
                closeModal('modalViewObat');
                editObat(currentObatData);
            };

            openModal('modalViewObat');
        }

        // 2. Edit Obat (Logika utama)
        function editObat(data) {
            currentObatData = data;
            
            const form = document.getElementById('formEditObat');
            // FIX FINAL: Menggunakan URI Statis untuk menghindari UrlGenerationException
            form.action = "/admin/obat/" + data.id; 
            
            document.getElementById('edit_id').value = data.id; 
            document.getElementById('edit_code').value = data.code;
            document.getElementById('edit_name').value = data.name;
            document.getElementById('edit_category').value = data.category;
            document.getElementById('edit_unit').value = data.unit; 
            document.getElementById('edit_stock').value = data.stock;
            document.getElementById('edit_unit_price').value = data.unit_price;
            document.getElementById('edit_exp_date').value = data.exp;
            document.getElementById('edit_description').value = data.description || '';
            
            openModal('modalEditObat');
        }

        // 3. Delete Obat (Konfirmasi)
        function deleteObat(id, info) {
            document.getElementById('delete_obat_info').textContent = info;
            
            const form = document.getElementById('formDeleteObat');
            // FIX FINAL: Menggunakan URI Statis untuk menghindari UrlGenerationException
            form.action = "/admin/obat/" + id; 
            
            openModal('modalDeleteObat');
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
        });
    </script>

@endsection