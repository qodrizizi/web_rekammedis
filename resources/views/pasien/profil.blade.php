@extends('layouts.app')

@section('title', 'Profil Saya & Pengaturan')

@section('content')

    <div class="space-y-6 max-w-5xl mx-auto py-8 px-4">
        
        <!-- Pesan Status & Error -->
        @if (session('success'))
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-4 rounded-2xl shadow-lg border-l-4 border-green-500 animate-fade-in">
                <div class="flex items-center">
                    <i class="bi bi-check-circle-fill text-green-500 text-2xl mr-3"></i>
                    <p class="font-semibold text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-gradient-to-r from-red-50 to-pink-50 p-4 rounded-2xl shadow-lg border-l-4 border-red-500 animate-fade-in">
                <div class="flex items-center">
                    <i class="bi bi-exclamation-triangle-fill text-red-500 text-2xl mr-3"></i>
                    <p class="font-semibold text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        @endif
        @if ($errors->any())
            <div class="bg-gradient-to-r from-red-50 to-pink-50 p-4 rounded-2xl shadow-lg border-l-4 border-red-500 animate-fade-in">
                <div class="flex items-center">
                    <i class="bi bi-x-circle-fill text-red-500 text-2xl mr-3"></i>
                    <p class="font-semibold text-red-800">Mohon periksa kesalahan input di bawah ini.</p>
                </div>
            </div>
        @endif

        <!-- Header Halaman -->
        <header class="bg-gradient-to-br from-indigo-600 to-purple-600 p-8 rounded-3xl shadow-2xl text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -mr-32 -mt-32"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white opacity-5 rounded-full -ml-24 -mb-24"></div>
            <div class="relative z-10">
                <div class="flex items-center mb-3">
                    <div class="bg-white bg-opacity-20 p-4 rounded-2xl mr-4 backdrop-blur-sm">
                        <i class="bi bi-person-circle text-4xl"></i>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold mb-1">Kelola Profil Anda</h1>
                        <p class="text-indigo-100 text-lg">Perbarui informasi pribadi dan riwayat kesehatan Anda</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Konten Utama: Tabs Data Diri dan Data Kesehatan -->
        <div x-data="{ activeTab: 'data_diri' }" class="bg-white shadow-2xl rounded-3xl border border-gray-100 overflow-hidden">
            
            <!-- Tab Navigation -->
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                <nav class="flex space-x-2 p-6" aria-label="Tabs">
                    <button type="button" @click="activeTab = 'data_diri'" 
                            :class="activeTab === 'data_diri' ? 'bg-white text-indigo-600 shadow-lg scale-105' : 'text-gray-600 hover:bg-white hover:bg-opacity-50'"
                            class="flex-1 py-4 px-6 inline-flex items-center justify-center font-semibold text-base rounded-2xl transition-all duration-300 transform">
                        <i class="bi bi-person-badge-fill mr-2 text-xl"></i> Data Diri & Kontak
                    </button>
                    <button type="button" @click="activeTab = 'data_kesehatan'" 
                            :class="activeTab === 'data_kesehatan' ? 'bg-white text-indigo-600 shadow-lg scale-105' : 'text-gray-600 hover:bg-white hover:bg-opacity-50'"
                            class="flex-1 py-4 px-6 inline-flex items-center justify-center font-semibold text-base rounded-2xl transition-all duration-300 transform">
                        <i class="bi bi-heart-pulse-fill mr-2 text-xl"></i> Data Kesehatan
                    </button>
                </nav>
            </div>

            <div class="p-8">
                
                {{-- Form Utama Profil --}}
                <form action="{{ route('pasien.profil.store') }}" method="POST" class="space-y-8">
                    @csrf
                    
                    <!-- 1. TAB: Data Diri & Kontak -->
                    <div x-show="activeTab === 'data_diri'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                        
                        <!-- Section: Informasi Akun -->
                        <div class="mb-8">
                            <div class="flex items-center mb-4">
                                <div class="bg-indigo-100 p-2 rounded-xl mr-3">
                                    <i class="bi bi-shield-lock-fill text-indigo-600 text-xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800">Informasi Akun</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Nama Lengkap --}}
                                <div class="group">
                                    <label for="nama_lengkap" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                        <i class="bi bi-person-fill text-indigo-500 mr-2"></i>Nama Lengkap
                                    </label>
                                    <div class="relative">
                                        <input type="text" id="nama_lengkap" value="{{ $user->name ?? '' }}" readonly
                                            class="w-full p-4 pl-12 border-2 border-gray-200 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl text-gray-600 font-medium cursor-not-allowed">
                                        <i class="bi bi-lock-fill absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                    </div>
                                </div>

                                {{-- Email --}}
                                <div class="group">
                                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                        <i class="bi bi-envelope-fill text-indigo-500 mr-2"></i>Alamat Email
                                    </label>
                                    <div class="relative">
                                        <input type="email" id="email" value="{{ $user->email ?? '' }}" readonly
                                            class="w-full p-4 pl-12 border-2 border-gray-200 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl text-gray-600 font-medium cursor-not-allowed">
                                        <i class="bi bi-lock-fill absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section: Identitas Pribadi -->
                        <div class="mb-8">
                            <div class="flex items-center mb-4">
                                <div class="bg-blue-100 p-2 rounded-xl mr-3">
                                    <i class="bi bi-card-checklist text-blue-600 text-xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800">Identitas Pribadi</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- NIK --}}
                                <div class="group">
                                    <label for="nik" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                        <i class="bi bi-credit-card-2-front-fill text-blue-500 mr-2"></i>
                                        Nomor Induk Kependudukan (NIK) 
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <input type="text" name="nik" id="nik" value="{{ old('nik', $patient->nik ?? '') }}" required
                                        class="w-full p-4 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all duration-300 hover:border-indigo-400 @error('nik') border-red-400 bg-red-50 @enderror"
                                        placeholder="16 digit NIK">
                                    @error('nik') 
                                        <p class="text-sm text-red-600 mt-2 flex items-center">
                                            <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                                        </p> 
                                    @enderror
                                </div>

                                {{-- Tanggal Lahir --}}
                                <div class="group">
                                    <label for="tanggal_lahir" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                        <i class="bi bi-calendar-event-fill text-blue-500 mr-2"></i>
                                        Tanggal Lahir 
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" 
                                        value="{{ old('tanggal_lahir', $patient->tanggal_lahir?->format('Y-m-d') ?? '') }}" required
                                        class="w-full p-4 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all duration-300 hover:border-indigo-400 @error('tanggal_lahir') border-red-400 bg-red-50 @enderror">
                                    @error('tanggal_lahir') 
                                        <p class="text-sm text-red-600 mt-2 flex items-center">
                                            <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                                        </p> 
                                    @enderror
                                </div>
                                
                                {{-- Jenis Kelamin --}}
                                <div class="group">
                                    <label for="jenis_kelamin" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                        <i class="bi bi-gender-ambiguous text-blue-500 mr-2"></i>
                                        Jenis Kelamin 
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <select name="jenis_kelamin" id="jenis_kelamin" required
                                        class="w-full p-4 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all duration-300 hover:border-indigo-400 @error('jenis_kelamin') border-red-400 bg-red-50 @enderror">
                                        @php $jk = old('jenis_kelamin', $patient->jenis_kelamin ?? ''); @endphp
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="L" {{ $jk == 'L' ? 'selected' : '' }}>👨 Laki-laki</option>
                                        <option value="P" {{ $jk == 'P' ? 'selected' : '' }}>👩 Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin') 
                                        <p class="text-sm text-red-600 mt-2 flex items-center">
                                            <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                                        </p> 
                                    @enderror
                                </div>

                                {{-- No. BPJS --}}
                                <div class="group">
                                    <label for="no_bpjs" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                        <i class="bi bi-credit-card text-blue-500 mr-2"></i>
                                        Nomor BPJS/Asuransi
                                    </label>
                                    <input type="text" name="no_bpjs" id="no_bpjs" value="{{ old('no_bpjs', $patient->no_bpjs ?? '') }}" 
                                        placeholder="Masukkan Nomor BPJS"
                                        class="w-full p-4 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all duration-300 hover:border-indigo-400 @error('no_bpjs') border-red-400 bg-red-50 @enderror">
                                    @error('no_bpjs') 
                                        <p class="text-sm text-red-600 mt-2 flex items-center">
                                            <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                                        </p> 
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section: Informasi Kontak -->
                        <div>
                            <div class="flex items-center mb-4">
                                <div class="bg-green-100 p-2 rounded-xl mr-3">
                                    <i class="bi bi-telephone-fill text-green-600 text-xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800">Informasi Kontak</h3>
                            </div>
                            <div class="grid grid-cols-1 gap-6">
                                {{-- Nomor Telepon --}}
                                <div class="group">
                                    <label for="no_hp" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                        <i class="bi bi-phone-fill text-green-500 mr-2"></i>
                                        Nomor Telepon 
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <input type="tel" name="no_hp" id="no_hp" value="{{ old('no_hp', $patient->no_hp ?? '') }}" required
                                        placeholder="Contoh: 08123456789"
                                        class="w-full p-4 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all duration-300 hover:border-indigo-400 @error('no_hp') border-red-400 bg-red-50 @enderror">
                                    @error('no_hp') 
                                        <p class="text-sm text-red-600 mt-2 flex items-center">
                                            <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                                        </p> 
                                    @enderror
                                </div>

                                {{-- Alamat --}}
                                <div class="group">
                                    <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                        <i class="bi bi-house-fill text-green-500 mr-2"></i>
                                        Alamat Lengkap
                                    </label>
                                    <textarea name="alamat" id="alamat" rows="4" 
                                        placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota"
                                        class="w-full p-4 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all duration-300 hover:border-indigo-400 resize-none @error('alamat') border-red-400 bg-red-50 @enderror">{{ old('alamat', $patient->alamat ?? '') }}</textarea>
                                    @error('alamat') 
                                        <p class="text-sm text-red-600 mt-2 flex items-center">
                                            <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                                        </p> 
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <!-- 2. TAB: Data Kesehatan Dasar -->
                    <div x-show="activeTab === 'data_kesehatan'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">

                        <!-- Section: Status Kesehatan -->
                        <div class="mb-8">
                            <div class="flex items-center mb-4">
                                <div class="bg-red-100 p-2 rounded-xl mr-3">
                                    <i class="bi bi-droplet-fill text-red-600 text-xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800">Status Kesehatan Dasar</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Golongan Darah --}}
                                <div class="group">
                                    <label for="gol_darah" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                        <i class="bi bi-droplet-half text-red-500 mr-2"></i>
                                        Golongan Darah
                                    </label>
                                    <select name="gol_darah" id="gol_darah"
                                        class="w-full p-4 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all duration-300 hover:border-indigo-400 @error('gol_darah') border-red-400 bg-red-50 @enderror">
                                        @php $gd = old('gol_darah', $patient->gol_darah ?? ''); @endphp
                                        <option value="">Pilih Golongan Darah</option>
                                        <option value="O" {{ $gd == 'O' ? 'selected' : '' }}>🩸 O</option>
                                        <option value="A" {{ $gd == 'A' ? 'selected' : '' }}>🩸 A</option>
                                        <option value="B" {{ $gd == 'B' ? 'selected' : '' }}>🩸 B</option>
                                        <option value="AB" {{ $gd == 'AB' ? 'selected' : '' }}>🩸 AB</option>
                                        <option value="N/A" {{ $gd == 'N/A' ? 'selected' : '' }}>❓ Belum Tahu</option>
                                    </select>
                                    @error('gol_darah') 
                                        <p class="text-sm text-red-600 mt-2 flex items-center">
                                            <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                                        </p> 
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section: Riwayat Penyakit -->
                        <div class="mb-8">
                            <div class="flex items-center mb-4">
                                <div class="bg-orange-100 p-2 rounded-xl mr-3">
                                    <i class="bi bi-clipboard2-pulse-fill text-orange-600 text-xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800">Riwayat Penyakit Kronis</h3>
                            </div>
                            <div class="group">
                                <label for="catatan_kronis" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                    <i class="bi bi-journal-medical text-orange-500 mr-2"></i>
                                    Catatan Penyakit Kronis/Penting
                                </label>
                                <textarea name="catatan_kronis" id="catatan_kronis" rows="4" 
                                    placeholder="Contoh: Diabetes Tipe 2 sejak 2020, Riwayat operasi usus buntu 2018."
                                    class="w-full p-4 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition-all duration-300 hover:border-indigo-400 resize-none"></textarea>
                                <div class="mt-3 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-xl">
                                    <p class="text-sm text-blue-800 flex items-start">
                                        <i class="bi bi-info-circle-fill mr-2 mt-0.5 flex-shrink-0"></i>
                                        <span>Catatan: Untuk riwayat kronis dan alergi, data ini harus disimpan di tabel riwayat terpisah untuk akurasi rekam medis.</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Section: Riwayat Alergi -->
                        <div>
                            <div class="flex items-center mb-4">
                                <div class="bg-red-100 p-2 rounded-xl mr-3">
                                    <i class="bi bi-exclamation-triangle-fill text-red-600 text-xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800">Riwayat Alergi</h3>
                            </div>
                            <div class="group">
                                <label for="alergi_obat" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                    <i class="bi bi-capsule text-red-500 mr-2"></i>
                                    Daftar Obat/Makanan yang Menyebabkan Alergi
                                </label>
                                <textarea name="alergi_obat" id="alergi_obat" rows="4" 
                                    placeholder="Contoh: Amoxicillin (reaksi ruam), Udang (gatal-gatal)"
                                    class="w-full p-4 border-2 border-red-300 rounded-xl focus:ring-4 focus:ring-red-100 focus:border-red-500 transition-all duration-300 hover:border-red-400 resize-none bg-red-50"></textarea>
                                <div class="mt-3 bg-red-100 border-l-4 border-red-500 p-4 rounded-r-xl">
                                    <p class="text-sm text-red-800 font-semibold flex items-start">
                                        <i class="bi bi-exclamation-octagon-fill mr-2 mt-0.5 flex-shrink-0 text-lg"></i>
                                        <span>INFORMASI INI SANGAT PENTING! Harap informasikan langsung kepada Dokter saat konsultasi.</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Submit Universal --}}
                    <div class="pt-6 border-t-2 border-gray-200">
                        <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-5 rounded-2xl transition-all duration-300 flex items-center justify-center shadow-xl hover:shadow-2xl transform hover:scale-105">
                            <i class="bi bi-save-fill mr-3 text-xl"></i> 
                            <span class="text-lg">Simpan Semua Perubahan Profil</span>
                        </button>
                        <p class="text-center text-sm text-gray-500 mt-3">
                            <i class="bi bi-shield-check mr-1"></i>
                            Data Anda akan tersimpan dengan aman
                        </p>
                    </div>

                </form>

            </div>

        </div>
    </div>

@endsection