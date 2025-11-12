@extends('layouts.app')

@section('title', 'Pendaftaran Pasien & Kunjungan')

@section('content')

    <div class="space-y-6 max-w-5xl mx-auto">
        
        <!-- Header Halaman -->
        <header class="bg-white p-6 rounded-2xl shadow-xl border-l-8 border-yellow-500 border-t border-gray-100">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center mb-1">
                <i class="bi bi-person-plus-fill text-yellow-600 mr-3"></i> Formulir Pendaftaran Pasien
            </h1>
            <p class="text-lg text-gray-600">Lengkapi data untuk mencatat pasien baru atau mendaftarkan kunjungan.</p>
        </header>

        <!-- Konten Utama: Tabs Pendaftaran -->
        <div x-data="{ activeTab: 'new_patient' }" class="bg-white shadow-xl rounded-2xl border border-gray-100">
            
            <!-- Tab Navigation -->
            <div class="border-b border-gray-200">
                <nav class="flex space-x-4 p-4" aria-label="Tabs">
                    <button @click="activeTab = 'new_patient'" 
                            :class="activeTab === 'new_patient' ? 'border-primary text-primary bg-primary/10' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                            class="py-2 px-4 inline-flex items-center font-medium text-sm rounded-xl border-b-2 transition-colors duration-200">
                        <i class="bi bi-person-plus mr-2"></i> Pendaftaran Pasien BARU
                    </button>
                    <button @click="activeTab = 'old_visit'" 
                            :class="activeTab === 'old_visit' ? 'border-primary text-primary bg-primary/10' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                            class="py-2 px-4 inline-flex items-center font-medium text-sm rounded-xl border-b-2 transition-colors duration-200">
                        <i class="bi bi-calendar-check mr-2"></i> Pendaftaran Kunjungan (LAMA)
                    </button>
                </nav>
            </div>

            <div class="p-6">
                
                <!-- 1. TAB: Pendaftaran Pasien Baru -->
                <div x-show="activeTab === 'new_patient'" class="space-y-6">
                    <h2 class="text-xl font-semibold text-gray-800">1. Data Diri Pasien</h2>
                    <form class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        {{-- Nama Lengkap --}}
                        <div>
                            <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" id="nama_lengkap" placeholder="Sesuai KTP/Kartu Identitas" required
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition-all">
                        </div>

                        {{-- NIK --}}
                        <div>
                            <label for="nik" class="block text-sm font-medium text-gray-700 mb-1">Nomor Induk Kependudukan (NIK)</label>
                            <input type="text" id="nik" placeholder="Contoh: 32xxxxxxxxxxxxxx" 
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition-all">
                        </div>

                        {{-- Tanggal Lahir --}}
                        <div>
                            <label for="tgl_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir <span class="text-red-500">*</span></label>
                            <input type="date" id="tgl_lahir" required
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition-all">
                        </div>
                        
                        {{-- Jenis Kelamin --}}
                        <div>
                            <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                            <select id="jenis_kelamin" required
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition-all">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        
                        {{-- Alamat --}}
                        <div class="md:col-span-2">
                            <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                            <textarea id="alamat" rows="2" placeholder="Jalan, RT/RW, Kelurahan, Kecamatan"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition-all"></textarea>
                        </div>

                        <div class="md:col-span-2 border-t border-gray-200 pt-6 space-y-4">
                            <h2 class="text-xl font-semibold text-gray-800">2. Data Kunjungan dan Asuransi</h2>
                        </div>
                        
                        {{-- Jenis Pembayaran --}}
                        <div>
                            <label for="pembayaran" class="block text-sm font-medium text-gray-700 mb-1">Jenis Pembayaran <span class="text-red-500">*</span></label>
                            <select id="pembayaran" required
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition-all">
                                <option value="umum">Umum (Tunai/Debit)</option>
                                <option value="bpjs">BPJS Kesehatan</option>
                                <option value="asuransi">Asuransi Lain</option>
                            </select>
                        </div>

                        {{-- No. BPJS (Conditional) --}}
                        <div>
                            <label for="no_bpjs" class="block text-sm font-medium text-gray-700 mb-1">Nomor BPJS (Jika menggunakan)</label>
                            <input type="text" id="no_bpjs" placeholder="13 digit angka BPJS"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition-all">
                        </div>

                        {{-- Tujuan Poli --}}
                        <div>
                            <label for="poli_tujuan" class="block text-sm font-medium text-gray-700 mb-1">Poli Tujuan <span class="text-red-500">*</span></label>
                            <select id="poli_tujuan" required
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition-all">
                                <option value="">Pilih Poli</option>
                                <option value="umum">Poli Umum</option>
                                <option value="gigi">Poli Gigi</option>
                                <option value="anak">Poli Anak</option>
                                <option value="dalam">Poli Penyakit Dalam</option>
                            </select>
                        </div>
                        
                        {{-- Dokter Tujuan --}}
                        <div>
                            <label for="dokter_tujuan" class="block text-sm font-medium text-gray-700 mb-1">Dokter Tujuan</label>
                            <select id="dokter_tujuan"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition-all">
                                <option value="">Pilih Dokter (Opsional)</option>
                                <option value="rian">Dr. Rian Setiawan, Sp.PD</option>
                                <option value="amelia">Dr. Amelia (Poli Gigi)</option>
                            </select>
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="md:col-span-2 pt-4">
                            <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-3 rounded-xl transition-colors duration-300 flex items-center justify-center shadow-lg">
                                <i class="bi bi-file-earmark-plus-fill mr-2"></i> Simpan Pasien Baru & Daftarkan Kunjungan
                            </button>
                        </div>
                    </form>
                </div>

                
                <!-- 2. TAB: Pendaftaran Kunjungan (Pasien Lama) -->
                <div x-show="activeTab === 'old_visit'" class="space-y-6" style="display: none;">
                    <h2 class="text-xl font-semibold text-gray-800">1. Cari Pasien Lama</h2>
                    
                    {{-- Form Pencarian Pasien --}}
                    <form class="flex flex-col sm:flex-row gap-3">
                        <input type="text" placeholder="Masukkan ID Pasien/NIK/Nama Pasien" 
                            class="flex-grow p-3 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition-all">
                        <button type="button" class="bg-primary hover:bg-secondary text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-300 flex items-center justify-center shadow-md">
                            <i class="bi bi-search mr-2"></i> Cari Pasien
                        </button>
                    </form>

                    {{-- Hasil Pencarian Placeholder --}}
                    <div class="bg-gray-50 p-4 rounded-lg border border-dashed border-gray-300 space-y-3">
                        <p class="font-semibold text-gray-800">Hasil Ditemukan (1): Budi Santoso (P1029)</p>
                        <p class="text-sm text-gray-600">Umur: 35 Tahun | Pembayaran: BPJS Kesehatan</p>
                        
                        <div class="border-t border-gray-200 pt-3 space-y-4">
                            <h2 class="text-xl font-semibold text-gray-800">2. Data Kunjungan</h2>

                            <form class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Tujuan Poli --}}
                                <div>
                                    <label for="poli_tujuan_lama" class="block text-sm font-medium text-gray-700 mb-1">Poli Tujuan <span class="text-red-500">*</span></label>
                                    <select id="poli_tujuan_lama" required
                                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition-all">
                                        <option value="">Pilih Poli</option>
                                        <option value="umum">Poli Umum</option>
                                        <option value="gigi">Poli Gigi</option>
                                        <option value="anak">Poli Anak</option>
                                        <option value="dalam">Poli Penyakit Dalam</option>
                                    </select>
                                </div>
                                
                                {{-- Dokter Tujuan --}}
                                <div>
                                    <label for="dokter_tujuan_lama" class="block text-sm font-medium text-gray-700 mb-1">Dokter Tujuan</label>
                                    <select id="dokter_tujuan_lama"
                                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition-all">
                                        <option value="">Pilih Dokter (Opsional)</option>
                                        <option value="rian">Dr. Rian Setiawan, Sp.PD</option>
                                        <option value="amelia">Dr. Amelia (Poli Gigi)</option>
                                    </select>
                                </div>

                                {{-- Tombol Submit --}}
                                <div class="md:col-span-2 pt-4">
                                    <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-3 rounded-xl transition-colors duration-300 flex items-center justify-center shadow-lg">
                                        <i class="bi bi-calendar-plus-fill mr-2"></i> Daftarkan Kunjungan Pasien Lama
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

@endsection