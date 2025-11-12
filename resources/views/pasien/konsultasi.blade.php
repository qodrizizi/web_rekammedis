@extends('layouts.app')

@section('title', 'Pendaftaran Konsultasi & Janji Temu')

@section('content')

    <div class="space-y-6 max-w-5xl mx-auto">
        
        <!-- Header Halaman -->
        <header class="bg-white p-6 rounded-2xl shadow-xl border-l-8 border-indigo-500 border-t border-gray-100">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center mb-1">
                <i class="bi bi-calendar-heart-fill text-indigo-600 mr-3"></i> Jadwal Konsultasi
            </h1>
            <p class="text-lg text-gray-600">Pilih jenis layanan dan dokter untuk konsultasi Anda.</p>
        </header>

        <!-- Area Status Janji Temu Aktif -->
        <div class="bg-indigo-50 p-4 rounded-xl shadow-md border border-indigo-200 flex justify-between items-center">
            <div>
                <p class="font-semibold text-indigo-700">Janji Temu Aktif:</p>
                <p class="text-sm text-indigo-600">Anda memiliki 1 janji pada **12 November 2025, 10:45** dengan Dr. Rian Setiawan.</p>
            </div>
            <a href="#" class="bg-indigo-500 hover:bg-indigo-600 text-white font-medium py-2 px-4 rounded-lg text-sm transition-colors">
                Kelola Janji
            </a>
        </div>

        <!-- Konten Utama: Tabs Pendaftaran -->
        <div x-data="{ activeTab: 'tatap_muka' }" class="bg-white shadow-xl rounded-2xl border border-gray-100">
            
            <!-- Tab Navigation -->
            <div class="border-b border-gray-200">
                <nav class="flex space-x-4 p-4" aria-label="Tabs">
                    <button @click="activeTab = 'tatap_muka'" 
                            :class="activeTab === 'tatap_muka' ? 'border-indigo-500 text-indigo-700 bg-indigo-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                            class="py-2 px-4 inline-flex items-center font-medium text-sm rounded-xl border-b-2 transition-colors duration-200">
                        <i class="bi bi-hospital-fill mr-2"></i> Konsultasi Tatap Muka (On-Site)
                    </button>
                    <button @click="activeTab = 'online'" 
                            :class="activeTab === 'online' ? 'border-indigo-500 text-indigo-700 bg-indigo-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                            class="py-2 px-4 inline-flex items-center font-medium text-sm rounded-xl border-b-2 transition-colors duration-200">
                        <i class="bi bi-camera-video-fill mr-2"></i> Konsultasi Online (Telemedis)
                    </button>
                </nav>
            </div>

            <div class="p-6">
                
                <!-- 1. TAB: Konsultasi Tatap Muka -->
                <div x-show="activeTab === 'tatap_muka'" class="space-y-6">
                    <h2 class="text-xl font-semibold text-gray-800">Pendaftaran Janji Temu di Klinik</h2>
                    <form class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 border border-gray-200 rounded-xl bg-gray-50">
                        
                        {{-- Poli Tujuan --}}
                        <div>
                            <label for="poli_tm" class="block text-sm font-medium text-gray-700 mb-1">Poli Tujuan <span class="text-red-500">*</span></label>
                            <select id="poli_tm" required
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                <option value="">Pilih Poli</option>
                                <option value="umum">Poli Umum</option>
                                <option value="gigi">Poli Gigi</option>
                                <option value="dalam">Poli Penyakit Dalam</option>
                            </select>
                        </div>

                        {{-- Dokter Pilihan --}}
                        <div>
                            <label for="dokter_tm" class="block text-sm font-medium text-gray-700 mb-1">Dokter Pilihan</label>
                            <select id="dokter_tm"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                <option value="">Pilih Dokter (Opsional)</option>
                                <option value="rian">Dr. Rian Setiawan, Sp.PD</option>
                                <option value="amelia">Dr. Amelia (Poli Gigi)</option>
                            </select>
                        </div>

                        {{-- Tanggal Kunjungan --}}
                        <div>
                            <label for="tgl_tm" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kunjungan <span class="text-red-500">*</span></label>
                            <input type="date" id="tgl_tm" required
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        </div>

                        {{-- Waktu Kunjungan (Slot) --}}
                        <div>
                            <label for="waktu_tm" class="block text-sm font-medium text-gray-700 mb-1">Waktu Pilihan (Slot Tersedia) <span class="text-red-500">*</span></label>
                            <select id="waktu_tm" required
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                <option value="">Pilih Slot</option>
                                <option value="1000">10:00 - 10:30 (Tersedia)</option>
                                <option value="1030">10:30 - 11:00 (Tersedia)</option>
                                <option value="1100" disabled>11:00 - 11:30 (Penuh)</option>
                            </select>
                        </div>
                        
                        {{-- Keluhan Singkat --}}
                        <div class="md:col-span-2">
                            <label for="keluhan_tm" class="block text-sm font-medium text-gray-700 mb-1">Keluhan Singkat</label>
                            <textarea id="keluhan_tm" rows="2" placeholder="Contoh: Sakit kepala dan demam ringan sejak 2 hari lalu."
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition-all"></textarea>
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="md:col-span-2 pt-4">
                            <button type="submit" class="w-full bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-3 rounded-xl transition-colors duration-300 flex items-center justify-center shadow-lg">
                                <i class="bi bi-calendar-plus-fill mr-2"></i> Ajukan Janji Temu Tatap Muka
                            </button>
                        </div>
                    </form>
                </div>

                
                <!-- 2. TAB: Konsultasi Online (Telemedis) -->
                <div x-show="activeTab === 'online'" class="space-y-6" style="display: none;">
                    <h2 class="text-xl font-semibold text-gray-800">Pendaftaran Sesi Telemedis</h2>
                    <form class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 border border-gray-200 rounded-xl bg-gray-50">
                        
                        {{-- Poli Tujuan Online --}}
                        <div>
                            <label for="poli_online" class="block text-sm font-medium text-gray-700 mb-1">Poli/Kategori Konsultasi <span class="text-red-500">*</span></label>
                            <select id="poli_online" required
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                <option value="">Pilih Kategori</option>
                                <option value="umum">Umum (24 Jam)</option>
                                <option value="spesialis">Spesialis (Jadwal)</option>
                                <option value="lanjutan">Tindak Lanjut Pasca Kunjungan</option>
                            </select>
                        </div>

                        {{-- Tanggal Konsultasi --}}
                        <div>
                            <label for="tgl_online" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Konsultasi <span class="text-red-500">*</span></label>
                            <input type="date" id="tgl_online" required
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        </div>

                        {{-- Keluhan Detail --}}
                        <div class="md:col-span-2">
                            <label for="keluhan_online" class="block text-sm font-medium text-gray-700 mb-1">Jelaskan Keluhan Anda (Wajib)</label>
                            <textarea id="keluhan_online" rows="4" placeholder="Jelaskan secara detail gejala, durasi, dan riwayat pengobatan yang sudah Anda lakukan."
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition-all" required></textarea>
                        </div>

                        {{-- Catatan Penting --}}
                        <div class="md:col-span-2">
                            <div class="p-3 bg-yellow-100 rounded-lg text-sm text-yellow-800 flex items-start">
                                <i class="bi bi-info-circle-fill text-xl mr-2 flex-shrink-0"></i>
                                <p>Konsultasi Online mungkin memerlukan pembayaran di muka. Dokter akan dihubungi setelah pembayaran diverifikasi.</p>
                            </div>
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="md:col-span-2 pt-4">
                            <button type="submit" class="w-full bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-3 rounded-xl transition-colors duration-300 flex items-center justify-center shadow-lg">
                                <i class="bi bi-camera-video-fill mr-2"></i> Ajukan Konsultasi Telemedis
                            </button>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </div>

@endsection