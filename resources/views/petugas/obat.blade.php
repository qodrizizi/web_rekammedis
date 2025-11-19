@extends('layouts.app')

@section('title', 'Manajemen Stok Obat & Farmasi')

@section('content')

    <div class="space-y-6">
        
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <i class="bi bi-capsule-pill text-red-600 mr-3"></i> Manajemen Stok Obat
            </h1>
            
            <div class="flex space-x-3">
                {{-- Tombol utama: Tambah Obat Baru (Memanggil Modal) --}}
                <button onclick="openModal('modalAddObat')" class="bg-primary hover:bg-secondary text-white font-semibold py-2 px-5 rounded-xl transition-colors duration-300 flex items-center shadow-md hover:shadow-lg">
                    <i class="bi bi-plus-circle-fill mr-2"></i> Tambah Jenis Obat
                </button>
                {{-- Tombol Aksi: Lihat Riwayat Log (Non-aktif) --}}
                <a href="#" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-5 rounded-xl transition-colors duration-300 flex items-center shadow-md">
                    <i class="bi bi-clock-history mr-2"></i> Log Stok
                </a>
            </div>
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

        {{-- 4 KOTAK STATISTIK (DATA DARI CONTROLLER) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 flex items-center justify-between border border-gray-100">
                <div>
                    <p class="text-sm font-medium text-gray-500">Item Stok Kritis</p>
                    <p class="text-3xl font-bold text-red-600 mt-1">{{ $stokKritisCount }}</p>
                </div>
                <div class="bg-red-500/10 p-3 rounded-xl text-red-600 flex items-center justify-center">
                    <i class="bi bi-exclamation-triangle-fill text-3xl"></i>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 flex items-center justify-between border border-gray-100">
                <div>
                    <p class="text-sm font-medium text-gray-500">Mendekati Kadaluarsa (60 Hari)</p>
                    <p class="text-3xl font-bold text-orange-600 mt-1">{{ $kadaluarsaCount }}</p>
                </div>
                <div class="bg-orange-500/10 p-3 rounded-xl text-orange-600 flex items-center justify-center">
                    <i class="bi bi-calendar-x-fill text-3xl"></i>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 flex items-center justify-between border border-gray-100">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Jenis Obat Aktif</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalJenisObat }}</p>
                </div>
                <div class="bg-blue-500/10 p-3 rounded-xl text-blue-600 flex items-center justify-center">
                    <i class="bi bi-database-fill text-3xl"></i>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 flex items-center justify-between border border-gray-100">
                <div>
                    <p class="text-sm font-medium text-gray-500">Resep Menunggu Disiapkan</p>
                    <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $resepMenungguCount }}</p>
                </div>
                <div class="bg-yellow-500/10 p-3 rounded-xl text-yellow-600 flex items-center justify-center">
                    <i class="bi bi-receipt-cutoff text-3xl"></i>
                </div>
            </div>
        </div>


        <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-100">
            
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0">
                
                <div class="w-full md:w-1/3">
                    <div class="relative">
                        <input type="text" placeholder="Cari berdasarkan Nama Obat, Kode..." 
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                            aria-label="Cari Obat">
                        <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600 hidden sm:block">Menampilkan {{ $medications->firstItem() ?? 0 }} - {{ $medications->lastItem() ?? 0 }} dari {{ $medications->total() }}</span>
                    
                    <select class="p-2 border border-gray-300 rounded-xl text-sm focus:ring-primary focus:border-primary transition-colors">
                        <option>Semua Kategori</option>
                        <option>Stok Kritis</option>
                        <option>Akan Kadaluarsa</option>
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-16">
                                Kode
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Nama Obat & Satuan
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider hidden sm:table-cell">
                                Stok Saat Ini
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider hidden lg:table-cell">
                                Batas Stok (Minimum)
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider hidden md:table-cell">
                                Tgl. Kadaluarsa
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-32">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        
                        @forelse($medications as $med)
                        @php
                            // Logika untuk status
                            $isKritis = $med->stok <= $med->stok_minimum;
                            $isKadaluarsa = $med->tanggal_kedaluwarsa && $med->tanggal_kedaluwarsa->isBefore(now()->addDays(60));
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors @if($isKritis || $isKadaluarsa) bg-red-50/50 @endif">
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $med->kode_obat }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">{{ $med->nama_obat }}</div>
                                <div class="text-xs text-gray-500">{{ $med->satuan }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold 
                                @if($isKritis) text-red-600 @else text-green-600 @endif hidden sm:table-cell">
                                {{ number_format($med->stok) }}
                                @if($isKritis)
                                    <i class="bi bi-exclamation-triangle-fill text-red-600 ml-1" title="Stok Kritis!"></i>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 hidden lg:table-cell">
                                {{ number_format($med->stok_minimum) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm hidden md:table-cell">
                                @if($med->tanggal_kedaluwarsa)
                                    @if($isKadaluarsa)
                                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2 py-1 rounded-full">
                                            {{ $med->tanggal_kedaluwarsa->isoFormat('D MMM YYYY') }}
                                        </span>
                                    @else
                                        <span class="text-gray-700 text-xs font-medium">{{ $med->tanggal_kedaluwarsa->isoFormat('D MMM YYYY') }}</span>
                                    @endif
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                @php
                                    // Siapkan data JSON untuk modal
                                    $dataJson = [
                                        'id' => $med->id,
                                        'kode_obat' => $med->kode_obat,
                                        'nama_obat' => $med->nama_obat,
                                        'satuan' => $med->satuan,
                                        'stok' => $med->stok,
                                        'stok_minimum' => $med->stok_minimum,
                                        'harga' => $med->harga,
                                        'tanggal_kedaluwarsa' => $med->tanggal_kedaluwarsa ? $med->tanggal_kedaluwarsa->format('Y-m-d') : null,
                                    ];
                                @endphp
                                <div class="flex justify-center space-x-1.5">
                                    {{-- Tombol Edit Data/Harga --}}
                                    <button 
                                        type="button"
                                        data-obat='@json($dataJson)'
                                        onclick="editObat(this)" 
                                        title="Edit Data Obat" class="text-yellow-600 hover:text-yellow-800 p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-pencil-square text-lg"></i>
                                    </button>
                                    {{-- Tombol Hapus --}}
                                    <button 
                                        type="button"
                                        onclick="deleteObat('{{ $med->id }}', '{{ $med->nama_obat }}')"
                                        title="Hapus Obat" class="text-red-600 hover:text-red-800 p-1.5 rounded-full hover:bg-gray-100 transition-colors">
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
            
            <div class="mt-6 flex justify-between items-center">
                <p class="text-sm text-gray-600">Total jenis: {{ $medications->total() }}</p>
                {{-- Pagination Links --}}
                {{ $medications->links('pagination::tailwind') }}
            </div>

        </div>

    </div>

    {{-- =================================================================
        MODAL TAMBAH, EDIT, DELETE (Disalin dari Pasien, disesuaikan)
    ================================================================== --}}

    {{-- Modal Tambah Obat Baru --}}
    <div id="modalAddObat" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 rounded-t-2xl">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="bi bi-plus-circle-fill text-primary mr-3"></i> Tambah Jenis Obat Baru
                    </h2>
                    <button type="button" onclick="closeModal('modalAddObat')" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="bi bi-x-lg text-2xl"></i>
                    </button>
                </div>
            </div>
            
            {{-- Form action disesuaikan dengan rute Anda (admin/petugas) --}}
            <form id="formAddObat" action="{{ url('petugas/obat') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kode Obat <span class="text-red-500">*</span></label>
                        <input type="text" name="kode_obat" placeholder="Contoh: PCL001" required value="{{ old('kode_obat') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Obat <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_obat" placeholder="Paracetamol 500mg" required value="{{ old('nama_obat') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Satuan <span class="text-red-500">*</span></label>
                        <input type="text" name="satuan" placeholder="Tablet / Botol / Strip" required value="{{ old('satuan') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Harga (Per Satuan) <span class="text-red-500">*</span></label>
                        <input type="number" name="harga" placeholder="Contoh: 5000" required value="{{ old('harga') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tgl. Kadaluarsa</label>
                        <input type="date" name="tanggal_kedaluwarsa" value="{{ old('tanggal_kedaluwarsa') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Stok Awal <span class="text-red-500">*</span></label>
                        <input type="number" name="stok" placeholder="Stok saat ini" required value="{{ old('stok', 0) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Stok Minimum <span class="text-red-500">*</span></label>
                        <input type="number" name="stok_minimum" placeholder="Batas stok kritis" required value="{{ old('stok_minimum', 10) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModal('modalAddObat')"
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-semibold">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-6 py-2 bg-primary text-white rounded-xl hover:bg-secondary transition-colors font-semibold shadow-md hover:shadow-lg">
                        <i class="bi bi-check-lg mr-2"></i> Simpan Obat Baru
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
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kode Obat <span class="text-red-500">*</span></label>
                        <input type="text" id="edit_kode_obat" name="kode_obat" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Obat <span class="text-red-500">*</span></label>
                        <input type="text" id="edit_nama_obat" name="nama_obat" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Satuan <span class="text-red-500">*</span></label>
                        <input type="text" id="edit_satuan" name="satuan" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Harga (Per Satuan) <span class="text-red-500">*</span></label>
                        <input type="number" id="edit_harga" name="harga" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tgl. Kadaluarsa</label>
                        <input type="date" id="edit_tanggal_kedaluwarsa" name="tanggal_kedaluwarsa"
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Stok Saat Ini <span class="text-red-500">*</span></label>
                        <input type="number" id="edit_stok" name="stok" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Stok Minimum <span class="text-red-500">*</span></label>
                        <input type="number" id="edit_stok_minimum" name="stok_minimum" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
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

    {{-- Modal Konfirmasi Hapus Obat --}}
    <div id="modalDeleteObat" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
            <div class="p-6">
                <div class="flex items-center justify-center w-16 h-16 mx-auto bg-red-100 rounded-full mb-4">
                    <i class="bi bi-exclamation-triangle-fill text-red-600 text-3xl"></i>
                </div>
                
                <h3 class="text-xl font-bold text-gray-800 text-center mb-2">Konfirmasi Hapus</h3>
                <p class="text-gray-600 text-center mb-1">Apakah Anda yakin ingin menghapus data obat:</p>
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
        // Fungsi Modal Universal
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
                @if (!$errors->any())
                    form.reset();
                @endif
            }
        }

        // 1. Edit Obat (mengisi form modal edit)
        function editObat(buttonElement) {
            const dataString = buttonElement.getAttribute('data-obat');
            const data = JSON.parse(dataString);
            
            const form = document.getElementById('formEditObat');
            // Pastikan URL ini sesuai dengan rute Anda
            form.action = "{{ url('petugas/obat') }}/" + data.id; 
            
            document.getElementById('edit_kode_obat').value = data.kode_obat;
            document.getElementById('edit_nama_obat').value = data.nama_obat;
            document.getElementById('edit_satuan').value = data.satuan;
            document.getElementById('edit_harga').value = data.harga;
            document.getElementById('edit_tanggal_kedaluwarsa').value = data.tanggal_kedaluwarsa;
            document.getElementById('edit_stok').value = data.stok;
            document.getElementById('edit_stok_minimum').value = data.stok_minimum;
            
            openModal('modalEditObat');
        }

        // 2. Delete Obat (mengisi konfirmasi modal)
        function deleteObat(id, info) {
            document.getElementById('delete_obat_info').textContent = info;
            const form = document.getElementById('formDeleteObat');
            // Pastikan URL ini sesuai dengan rute Anda
            form.action = "{{ url('petugas/obat') }}/" + id; 
            openModal('modalDeleteObat');
        }
        
        // Event listener saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            // Tutup modal ketika klik di luar
            document.querySelectorAll('[id^="modal"]').forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeModal(this.id);
                    }
                });
            });

            // Keyboard shortcut - ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    document.querySelectorAll('[id^="modal"]:not(.hidden)').forEach(modal => {
                        closeModal(modal.id);
                    });
                }
            });

            // Buka modal 'Tambah' jika ada error validasi
            @if ($errors->any() && old('kode_obat'))
                openModal('modalAddObat');
            @endif
        });
    </script>

@endsection