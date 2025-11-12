@extends('layouts.app')

@section('title', 'Profil Saya & Pengaturan')

@section('content')

    <div class="space-y-6 max-w-4xl mx-auto">
        
        <!-- Header Halaman -->
        <header class="bg-white p-6 rounded-2xl shadow-xl border-l-8 border-indigo-500 border-t border-gray-100">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center mb-1">
                <i class="bi bi-file-person-fill text-indigo-600 mr-3"></i> Kelola Data Profil
            </h1>
            <p class="text-lg text-gray-600">Perbarui informasi identitas, kontak, dan riwayat kesehatan dasar Anda.</p>
        </header>

        <!-- Konten Utama: Tabs Data Diri dan Data Kesehatan -->
        <div x-data="{ activeTab: 'data_diri' }" class="bg-white shadow-xl rounded-2xl border border-gray-100">
            
            <!-- Tab Navigation -->
            <div class="border-b border-gray-200">
                <nav class="flex space-x-4 p-4" aria-label="Tabs">
                    <button @click="activeTab = 'data_diri'" 
                            :class="activeTab === 'data_diri' ? 'border-indigo-500 text-indigo-700 bg-indigo-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                            class="py-2 px-4 inline-flex items-center font-medium text-sm rounded-xl border-b-2 transition-colors duration-200">
                        <i class="bi bi-person-badge mr-2"></i> Data Diri & Kontak
                    </button>
                    <button @click="activeTab = 'data_kesehatan'" 
                            :class="activeTab === 'data_kesehatan' ? 'border-indigo-500 text-indigo-700 bg-indigo-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                            class="py-2 px-4 inline-flex items-center font-medium text-sm rounded-xl border-b-2 transition-colors duration-200">
                        <i class="bi bi-heart-pulse-fill mr-2"></i> Data Kesehatan Dasar
                    </button>
                </nav>
            </div>

            <div class="p-6">
                
                <!-- 1. TAB: Data Diri & Kontak -->
                <div x-show="activeTab === 'data_diri'" class="space-y-6">
                    <form class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        {{-- Nama Lengkap (Readonly) --}}
                        <div>
                            <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap (Tidak dapat diubah)</label>
                            <input type="text" id="nama_lengkap" value="Budi Santoso" readonly
                                class="w-full p-3 border border-gray-300 bg-gray-100 rounded-lg">
                        </div>

                        {{-- NIK (Readonly) --}}
                        <div>
                            <label for="nik" class="block text-sm font-medium text-gray-700 mb-1">Nomor Induk Kependudukan (NIK)</label>
                            <input type="text" id="nik" value="3201019876543210" readonly
                                class="w-full p-3 border border-gray-300 bg-gray-100 rounded-lg">
                        </div>

                        {{-- Tanggal Lahir (Readonly) --}}
                        <div>
                            <label for="tgl_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                            <input type="date" id="tgl_lahir" value="1990-05-15" readonly
                                class="w-full p-3 border border-gray-300 bg-gray-100 rounded-lg">
                        </div>
                        
                        {{-- No. BPJS --}}
                        <div>
                            <label for="no_bpjs" class="block text-sm font-medium text-gray-700 mb-1">Nomor BPJS/Asuransi Lain</label>
                            <input type="text" id="no_bpjs" value="0001234567890" placeholder="Masukkan Nomor BPJS Anda"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        </div>

                        {{-- Nomor Telepon --}}
                        <div>
                            <label for="telepon" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon <span class="text-red-500">*</span></label>
                            <input type="tel" id="telepon" value="081234567890" required
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        </div>
                        
                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                            <input type="email" id="email" value="budi.santoso@mail.com" placeholder="Email untuk notifikasi"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        </div>
                        
                        {{-- Alamat --}}
                        <div class="md:col-span-2">
                            <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                            <textarea id="alamat" rows="3" placeholder="Jalan, RT/RW, Kelurahan, Kecamatan"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition-all">Jl. Merdeka No. 12, RT 01/RW 03, Jakarta</textarea>
                        </div>

                        {{-- Kontak Darurat --}}
                        <div class="md:col-span-2 border-t border-gray-200 pt-6 space-y-4">
                            <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                                <i class="bi bi-person-heart text-red-500 mr-2"></i> Kontak Darurat
                            </h2>
                        </div>
                        
                        <div>
                            <label for="nama_darurat" class="block text-sm font-medium text-gray-700 mb-1">Nama Kontak Darurat</label>
                            <input type="text" id="nama_darurat" value="Ani" placeholder="Nama Kerabat/Keluarga"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        </div>
                        
                        <div>
                            <label for="telp_darurat" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon Darurat</label>
                            <input type="tel" id="telp_darurat" value="0811999000"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        </div>
                        
                        {{-- Tombol Submit --}}
                        <div class="md:col-span-2 pt-4">
                            <button type="submit" class="w-full bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-3 rounded-xl transition-colors duration-300 flex items-center justify-center shadow-lg">
                                <i class="bi bi-save-fill mr-2"></i> Simpan Perubahan Data Diri
                            </button>
                        </div>
                    </form>
                </div>

                
                <!-- 2. TAB: Data Kesehatan Dasar -->
                <div x-show="activeTab === 'data_kesehatan'" class="space-y-6" style="display: none;">
                    <form class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div class="md:col-span-2">
                            <h2 class="text-xl font-semibold text-gray-800 flex items-center mb-3">
                                <i class="bi bi-bandaid-fill text-green-500 mr-2"></i> Status Kesehatan Dasar
                            </h2>
                        </div>

                        {{-- Golongan Darah --}}
                        <div>
                            <label for="gol_darah" class="block text-sm font-medium text-gray-700 mb-1">Golongan Darah</label>
                            <select id="gol_darah"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                <option value="O">O</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="AB">AB</option>
                                <option value="N/A">Belum Tahu</option>
                            </select>
                        </div>
                        
                        {{-- Riwayat Penyakit Kronis --}}
                        <div>
                            <label for="riwayat_kronis" class="block text-sm font-medium text-gray-700 mb-1">Riwayat Penyakit Kronis</label>
                            <select id="riwayat_kronis"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                <option value="tidak">Tidak Ada</option>
                                <option value="diabetes">Diabetes</option>
                                <option value="hipertensi">Hipertensi</option>
                                <option value="jantung">Penyakit Jantung</option>
                                <option value="lain">Lain-lain (Jelaskan di bawah)</option>
                            </select>
                        </div>

                        {{-- Riwayat Alergi Obat --}}
                        <div class="md:col-span-2">
                            <h2 class="text-xl font-semibold text-gray-800 flex items-center mb-3 border-t border-gray-200 pt-6">
                                <i class="bi bi-bug-fill text-red-500 mr-2"></i> Riwayat Alergi (Obat/Makanan)
                            </h2>
                            <label for="alergi_obat" class="block text-sm font-medium text-gray-700 mb-1">Daftar Obat/Makanan yang menyebabkan Alergi:</label>
                            <textarea id="alergi_obat" rows="3" placeholder="Contoh: Amoxicillin (reaksi ruam), Udang (gatal-gatal)"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition-all">Amoxicillin (reaksi ruam)</textarea>
                            <p class="text-xs text-red-500 mt-1">INFORMASI INI SANGAT PENTING UNTUK KESELAMATAN ANDA. HARAP ISI DENGAN BENAR.</p>
                        </div>

                        {{-- Catatan Lain --}}
                        <div class="md:col-span-2">
                            <label for="catatan_lain" class="block text-sm font-medium text-gray-700 mb-1">Catatan Lain untuk Dokter/Perawat</label>
                            <textarea id="catatan_lain" rows="2" placeholder="Informasi tambahan seperti riwayat operasi, dll."
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition-all"></textarea>
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="md:col-span-2 pt-4">
                            <button type="submit" class="w-full bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-3 rounded-xl transition-colors duration-300 flex items-center justify-center shadow-lg">
                                <i class="bi bi-save-fill mr-2"></i> Simpan Data Kesehatan
                            </button>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </div>

@endsection