@extends('layouts.app')

@section('title', 'Pendaftaran Konsultasi & Janji Temu')

@section('content')

    <div class="space-y-6 max-w-5xl mx-auto">
        
        <header class="bg-white p-6 rounded-2xl shadow-xl border-l-8 border-indigo-500 border-t border-gray-100">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center mb-1">
                <i class="bi bi-calendar-heart-fill text-indigo-600 mr-3"></i> Jadwal Konsultasi
            </h1>
            <p class="text-lg text-gray-600">Pilih jenis layanan dan dokter untuk konsultasi Anda.</p>
        </header>
        
        @if (session('success'))
            <div class="bg-green-100 p-4 rounded-xl shadow-md border border-green-200 text-green-700">
                <p class="font-semibold">{{ session('success') }}</p>
            </div>
        @endif
        @if (session('info'))
            <div class="bg-blue-100 p-4 rounded-xl shadow-md border border-blue-200 text-blue-700">
                <p class="font-semibold">{{ session('info') }}</p>
            </div>
        @endif

        @if ($activeAppointment)
            <div class="bg-indigo-50 p-4 rounded-xl shadow-md border border-indigo-200 flex justify-between items-center">
                <div>
                    <p class="font-semibold text-indigo-700">Janji Temu Aktif:</p>
                    <p class="text-sm text-indigo-600">
                        Anda memiliki 1 janji pada 
                        **{{ \Carbon\Carbon::parse($activeAppointment->tanggal_kunjungan)->format('d F Y') }}, 
                        {{ \Carbon\Carbon::parse($activeAppointment->jam_kunjungan)->format('H:i') }}** dengan Dr. {{ $activeAppointment->doctor->user->name ?? 'N/A' }}. 
                        (Status: **{{ ucfirst($activeAppointment->status) }}**)
                    </p>
                </div>
                <a href="#" class="bg-indigo-500 hover:bg-indigo-600 text-white font-medium py-2 px-4 rounded-lg text-sm transition-colors">
                    Kelola Janji
                </a>
            </div>
        @else
            <div class="bg-gray-50 p-4 rounded-xl shadow-md border border-gray-200 text-gray-700">
                <p class="font-semibold">Tidak ada janji temu aktif.</p>
            </div>
        @endif


        <div x-data="{ activeTab: 'tatap_muka' }" class="bg-white shadow-xl rounded-2xl border border-gray-100">
            
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
                
                <div x-show="activeTab === 'tatap_muka'" class="space-y-6">
                    <h2 class="text-xl font-semibold text-gray-800">Pendaftaran Janji Temu di Klinik</h2>
                    
                    {{-- Form Tatap Muka --}}
                    <form action="{{ route('pasien.konsultasi.store.tm') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 border border-gray-200 rounded-xl bg-gray-50">
                        @csrf
                        
                        {{-- Poli Tujuan (Diambil dari DB: clinics) --}}
                        <div>
                            <label for="poli_tm" class="block text-sm font-medium text-gray-700 mb-1">Poli Tujuan <span class="text-red-500">*</span></label>
                            <select name="poli_tm" id="poli_tm" required
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition-all @error('poli_tm') border-red-500 @enderror">
                                <option value="">Pilih Poli</option>
                                @foreach ($clinics as $clinic)
                                    <option value="{{ $clinic->id }}" {{ old('poli_tm') == $clinic->id ? 'selected' : '' }}>
                                        {{ $clinic->nama_poli }}
                                    </option>
                                @endforeach
                            </select>
                            @error('poli_tm') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Dokter Pilihan (Diambil dari DB: doctors) --}}
                        <div>
                            <label for="dokter_tm" class="block text-sm font-medium text-gray-700 mb-1">Dokter Pilihan</label>
                            <select name="dokter_tm" id="dokter_tm"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition-all @error('dokter_tm') border-red-500 @enderror">
                                <option value="">Pilih Dokter (Opsional)</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ old('dokter_tm') == $doctor->id ? 'selected' : '' }}>
                                        Dr. {{ $doctor->user->name ?? 'N/A' }}, {{ $doctor->spesialis }}
                                    </option>
                                @endforeach
                            </select>
                            @error('dokter_tm') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Tanggal Kunjungan --}}
                        <div>
                            <label for="tgl_tm" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kunjungan <span class="text-red-500">*</span></label>
                            <input type="date" name="tgl_tm" id="tgl_tm" required value="{{ old('tgl_tm') }}"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition-all @error('tgl_tm') border-red-500 @enderror">
                            @error('tgl_tm') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Waktu Kunjungan (Slot) (Diambil dari $timeSlots Controller) --}}
                        <div>
                            <label for="waktu_tm" class="block text-sm font-medium text-gray-700 mb-1">Waktu Pilihan (Slot Tersedia) <span class="text-red-500">*</span></label>
                            <select name="waktu_tm" id="waktu_tm" required
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition-all @error('waktu_tm') border-red-500 @enderror">
                                <option value="">Pilih Slot</option>
                                @foreach ($timeSlots as $slot)
                                    <option value="{{ $slot['value'] }}" {{ $slot['disabled'] ? 'disabled' : '' }} {{ old('waktu_tm') == $slot['value'] ? 'selected' : '' }}>
                                        {{ $slot['label'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('waktu_tm') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        
                        {{-- Keluhan Singkat --}}
                        <div class="md:col-span-2">
                            <label for="keluhan_tm" class="block text-sm font-medium text-gray-700 mb-1">Keluhan Singkat</label>
                            <textarea name="keluhan_tm" id="keluhan_tm" rows="2" placeholder="Contoh: Sakit kepala dan demam ringan sejak 2 hari lalu."
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition-all @error('keluhan_tm') border-red-500 @enderror">{{ old('keluhan_tm') }}</textarea>
                            @error('keluhan_tm') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="md:col-span-2 pt-4">
                            <button type="submit" class="w-full bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-3 rounded-xl transition-colors duration-300 flex items-center justify-center shadow-lg">
                                <i class="bi bi-calendar-plus-fill mr-2"></i> Ajukan Janji Temu Tatap Muka
                            </button>
                        </div>
                    </form>
                </div>

                
                <div x-show="activeTab === 'online'" class="space-y-6" style="display: none;">
                    <h2 class="text-xl font-semibold text-gray-800">Pendaftaran Sesi Telemedis</h2>
                    
                    {{-- Form Online --}}
                    <form action="{{ route('pasien.konsultasi.store.online') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 border border-gray-200 rounded-xl bg-gray-50">
                        @csrf
                        
                        {{-- Poli Tujuan Online (Saat ini masih manual sesuai blade awal) --}}
                        <div>
                            <label for="poli_online" class="block text-sm font-medium text-gray-700 mb-1">Poli/Kategori Konsultasi <span class="text-red-500">*</span></label>
                            <select name="poli_online" id="poli_online" required
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition-all @error('poli_online') border-red-500 @enderror">
                                <option value="">Pilih Kategori</option>
                                <option value="umum" {{ old('poli_online') == 'umum' ? 'selected' : '' }}>Umum (24 Jam)</option>
                                <option value="spesialis" {{ old('poli_online') == 'spesialis' ? 'selected' : '' }}>Spesialis (Jadwal)</option>
                                <option value="lanjutan" {{ old('poli_online') == 'lanjutan' ? 'selected' : '' }}>Tindak Lanjut Pasca Kunjungan</option>
                            </select>
                            @error('poli_online') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Tanggal Konsultasi --}}
                        <div>
                            <label for="tgl_online" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Konsultasi <span class="text-red-500">*</span></label>
                            <input type="date" name="tgl_online" id="tgl_online" required value="{{ old('tgl_online') }}"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition-all @error('tgl_online') border-red-500 @enderror">
                            @error('tgl_online') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Keluhan Detail --}}
                        <div class="md:col-span-2">
                            <label for="keluhan_online" class="block text-sm font-medium text-gray-700 mb-1">Jelaskan Keluhan Anda (Wajib)</label>
                            <textarea name="keluhan_online" id="keluhan_online" rows="4" placeholder="Jelaskan secara detail gejala, durasi, dan riwayat pengobatan yang sudah Anda lakukan."
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition-all @error('keluhan_online') border-red-500 @enderror" required>{{ old('keluhan_online') }}</textarea>
                            @error('keluhan_online') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
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