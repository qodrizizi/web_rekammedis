@extends('layouts.app')

@section('title', 'Pendaftaran Pasien & Kunjungan')

@section('content')

    <div class="space-y-6 max-w-6xl mx-auto p-4 sm:p-6">
        
        {{-- Flash Message Success/Error --}}
        @if (session('success'))
            <div class="p-4 rounded-xl border-l-4 border-green-500 bg-green-50 text-green-800 shadow-md animate-fade-in" role="alert">
                <div class="flex items-start">
                    <i class="bi bi-check-circle-fill text-green-500 text-2xl mr-3"></i>
                    <div>
                        <strong class="font-bold block mb-1">Berhasil!</strong>
                        <span class="block text-sm">{{ session('success') }}</span>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="p-4 rounded-xl border-l-4 border-red-500 bg-red-50 text-red-800 shadow-md animate-fade-in" role="alert">
                <div class="flex items-start">
                    <i class="bi bi-exclamation-triangle-fill text-red-500 text-2xl mr-3"></i>
                    <div>
                        <strong class="font-bold block mb-1">Gagal!</strong>
                        <span class="block text-sm">{{ session('error') }}</span>
                    </div>
                </div>
            </div>
        @endif
        
        {{-- Header --}}
        <header class="bg-gradient-to-r from-indigo-600 to-indigo-800 p-8 rounded-2xl shadow-2xl text-white">
            <div class="flex items-center mb-2">
                <i class="bi bi-clipboard2-plus-fill text-5xl mr-4 opacity-90"></i>
                <div>
                    <h1 class="text-3xl font-extrabold">Pendaftaran Pasien</h1>
                    <p class="text-indigo-100 text-sm mt-1">Registrasi pasien dan janji kunjungan harian</p>
                </div>
            </div>
        </header>

        {{-- Main Form Container dengan Alpine.js --}}
        <div x-data="{ 
            activeTab: 'new_patient',
            
            // Data Dinamis
            // Data $clinics ini didapat dari PendaftaranController@index
            allClinics: {{ $clinics->isEmpty() ? '[]' : $clinics->toJson() }},
            doctors: [],
            
            // Model untuk form
            selectedClinicNew: '{{ old('poli_tujuan') }}',
            selectedDoctorNew: '{{ old('dokter_tujuan') }}',
            selectedClinicOld: '',
            selectedDoctorOld: '',

            // Status loading
            isLoadingDoctors: false,
            isSearching: false,
            
            // Data Pasien Lama
            isPatientFound: false,
            patientSearchTerm: '',
            patientData: {
                id: '', 
                name: '', 
                age: '', 
                nik: '',
                payment: ''
            },
            
            // Fungsi untuk memuat dokter berdasarkan poli
            // 'context' bisa 'new' atau 'old'
            loadDoctors(context) {
                let clinicId = (context === 'new') ? this.selectedClinicNew : this.selectedClinicOld;
                
                this.doctors = []; // Kosongkan dokter
                if (context === 'new') this.selectedDoctorNew = '';
                if (context === 'old') this.selectedDoctorOld = '';

                if (!clinicId) return;

                this.isLoadingDoctors = true;
                
                // Panggil API route yang kita buat di web.php
                fetch(`/petugas/api/doctors-by-clinic/${clinicId}`)
                    .then(response => response.json())
                    .then(data => {
                        this.doctors = data;
                        this.isLoadingDoctors = false;
                    })
                    .catch(error => {
                        console.error('Error loading doctors:', error);
                        this.isLoadingDoctors = false;
                    });
            },

            // Fungsi untuk mencari pasien lama (REAL)
            searchPatient() {
                if (this.patientSearchTerm.length < 3) return; // Minimal 3 karakter
                
                this.isSearching = true;
                this.isPatientFound = false;

                // Panggil API route yang kita buat di web.php
                fetch(`{{ route('petugas.api.search.patient') }}?q=${encodeURIComponent(this.patientSearchTerm)}`)
                    .then(response => {
                        if (response.ok) {
                            return response.json();
                        }
                        throw new Error('Patient not found');
                    })
                    .then(data => {
                        this.patientData = {
                            id: data.id, 
                            name: data.name, 
                            age: data.age, 
                            nik: data.nik,
                            payment: data.payment
                        };
                        this.isPatientFound = true;
                        this.isSearching = false;
                    })
                    .catch(error => {
                        console.error('Error searching patient:', error);
                        this.isPatientFound = false;
                        this.isSearching = false;
                    });
            },
            
            resetSearch() {
                this.isPatientFound = false;
                this.patientSearchTerm = '';
                this.patientData = { id: '', name: '', age: '', nik: '', payment: '' };
                this.selectedClinicOld = '';
                this.selectedDoctorOld = '';
                this.doctors = [];
            },
            
            resetNewPatientForm() {
                this.selectedClinicNew = '';
                this.selectedDoctorNew = '';
                this.doctors = [];
            }
        }" class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">
            
            {{-- Tab Navigation --}}
            <div class="border-b border-gray-200 bg-gray-50">
                <nav class="flex" aria-label="Tabs">
                    {{-- Tab Pasien BARU --}}
                    <button 
                        @click="activeTab = 'new_patient'; resetSearch();" 
                        :class="activeTab === 'new_patient' 
                            ? 'bg-white text-indigo-700 font-bold border-b-4 border-indigo-600 shadow-sm' 
                            : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-100'"
                        class="flex-1 py-4 px-6 inline-flex items-center justify-center text-base rounded-t-lg transition-all duration-200">
                        <i class="bi bi-person-plus-fill mr-2 text-xl"></i> 
                        <span class="hidden sm:inline">Pasien</span> BARU
                    </button>
                    
                    {{-- Tab Pasien LAMA --}}
                    <button 
                        @click="activeTab = 'old_visit'; resetNewPatientForm();" 
                        :class="activeTab === 'old_visit' 
                            ? 'bg-white text-indigo-700 font-bold border-b-4 border-indigo-600 shadow-sm' 
                            : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-100'"
                        class="flex-1 py-4 px-6 inline-flex items-center justify-center text-base rounded-t-lg transition-all duration-200">
                        <i class="bi bi-calendar-check-fill mr-2 text-xl"></i> 
                        Kunjungan <span class="hidden sm:inline">Pasien</span> LAMA
                    </button>
                </nav>
            </div>

            {{-- Tab Content --}}
            <div class="p-6 sm:p-8">
                
                {{-- ========== TAB 1: PASIEN BARU ========== --}}
                <div x-show="activeTab === 'new_patient'" 
                     x-cloak
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-y-4"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="space-y-6">
                    
                    <div class="flex items-center mb-6">
                        <div class="bg-indigo-100 p-3 rounded-full mr-4">
                            <i class="bi bi-person-plus text-indigo-600 text-2xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Data Pasien Baru</h2>
                            <p class="text-gray-600 text-sm">Lengkapi formulir untuk pendaftaran pertama kali</p>
                        </div>
                    </div>
                    
                    <form action="{{ route('petugas.pendaftaran.store.new') }}" method="POST" class="space-y-8">
                        @csrf
                        
                        {{-- Section 1: Data Diri Pasien --}}
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-xl border border-gray-200 space-y-5">
                            <div class="flex items-center mb-4">
                                <i class="bi bi-person-vcard text-indigo-600 text-xl mr-2"></i>
                                <h3 class="text-lg font-bold text-gray-800">Informasi Personal</h3>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="md:col-span-2">
                                    <label for="nama_lengkap" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Nama Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="nama_lengkap" name="nama_lengkap" 
                                        placeholder="Sesuai KTP/Kartu Identitas" required
                                        value="{{ old('nama_lengkap') }}"
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                </div>
                                
                                <div>
                                    <label for="nik" class="block text-sm font-semibold text-gray-700 mb-2">
                                        NIK (16 digit) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="nik" name="nik" 
                                        placeholder="3201234567891234" maxlength="16" required
                                        value="{{ old('nik') }}"
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                </div>
                                
                                <div>
                                    <label for="tgl_lahir" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Tanggal Lahir <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" id="tgl_lahir" name="tanggal_lahir" required
                                        value="{{ old('tanggal_lahir') }}"
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                </div>
                                
                                <div>
                                    <label for="jenis_kelamin" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Jenis Kelamin <span class="text-red-500">*</span>
                                    </label>
                                    <select id="jenis_kelamin" name="jenis_kelamin" required
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Email <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" id="email" name="email" 
                                        placeholder="pasien@email.com" required
                                        value="{{ old('email') }}"
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Alamat Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <textarea id="alamat" name="alamat" rows="3" required
                                        placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota"
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">{{ old('alamat') }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Section 2: Data Kunjungan --}}
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-6 rounded-xl border border-indigo-200 space-y-5">
                            <div class="flex items-center mb-4">
                                <i class="bi bi-hospital text-indigo-600 text-xl mr-2"></i>
                                <h3 class="text-lg font-bold text-gray-800">Informasi Kunjungan</h3>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label for="pembayaran" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Jenis Pembayaran <span class="text-red-500">*</span>
                                    </label>
                                    <select id="pembayaran" name="pembayaran" required
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                        <option value="umum" {{ old('pembayaran') == 'umum' ? 'selected' : '' }}>Umum (Tunai/Debit)</option>
                                        <option value="bpjs" {{ old('pembayaran') == 'bpjs' ? 'selected' : '' }}>BPJS Kesehatan</option>
                                        <option value="asuransi" {{ old('pembayaran') == 'asuransi' ? 'selected' : '' }}>Asuransi Lain</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="no_bpjs" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Nomor BPJS (Opsional)
                                    </label>
                                    <input type="text" id="no_bpjs" name="no_bpjs" 
                                        placeholder="13 digit nomor BPJS" maxlength="13"
                                        value="{{ old('no_bpjs') }}"
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                </div>
                                
                                <div>
                                    <label for="poli_tujuan" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Poli Tujuan <span class="text-red-500">*</span>
                                    </label>
                                    <select id="poli_tujuan" name="poli_tujuan" required
                                        x-model="selectedClinicNew"
                                        @change="loadDoctors('new')"
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                        <option value="">Pilih Poli</option>
                                        <template x-for="clinic in allClinics" :key="clinic.id">
                                            <option :value="clinic.id" x-text="clinic.nama_poli" :selected="clinic.id == selectedClinicNew"></option>
                                        </template>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="dokter_tujuan" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Dokter Tujuan <span class="text-red-500">*</span>
                                    </label>
                                    <select id="dokter_tujuan" name="dokter_tujuan" required
                                        x-model="selectedDoctorNew"
                                        :disabled="isLoadingDoctors || doctors.length === 0"
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all disabled:bg-gray-100">
                                        
                                        <option value="" x-show="selectedClinicNew === ''">Pilih Poli Terlebih Dahulu</option>
                                        <option value="" x-show="isLoadingDoctors">Memuat dokter...</option>
                                        <option value="" x-show="selectedClinicNew !== '' && !isLoadingDoctors && doctors.length === 0">Tidak ada dokter di poli ini</option>
                                        <option value="" x-show="selectedClinicNew !== '' && !isLoadingDoctors && doctors.length > 0">Pilih Dokter</option>
                                        
                                        <template x-for="doctor in doctors" :key="doctor.id">
                                            <option :value="doctor.id" x-text="doctor.name" :selected="doctor.id == selectedDoctorNew"></option>
                                        </template>
                                    </select>
                                </div>

                                <div class="md:col-span-2">
                                    <label for="tanggal_kunjungan" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Tanggal Kunjungan <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" id="tanggal_kunjungan" name="tanggal_kunjungan" required
                                        value="{{ old('tanggal_kunjungan', now()->format('Y-m-d')) }}" 
                                        min="{{ now()->format('Y-m-d') }}"
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                </div>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="pt-4">
                            <button type="submit" 
                                class="w-full bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-bold py-4 px-6 rounded-xl transition-all duration-300 flex items-center justify-center shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <i class="bi bi-check-circle-fill mr-3 text-xl"></i> 
                                SIMPAN & DAFTARKAN PASIEN BARU
                            </button>
                        </div>
                    </form>
                </div>

                {{-- ========== TAB 2: PASIEN LAMA ========== --}}
                <div x-show="activeTab === 'old_visit'" 
                     x-cloak
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-y-4"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="space-y-6">
                    
                    <div class="flex items-center mb-6">
                        <div class="bg-yellow-100 p-3 rounded-full mr-4">
                            <i class="bi bi-calendar-check text-yellow-600 text-2xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Kunjungan Pasien Lama</h2>
                            <p class="text-gray-600 text-sm">Cari data pasien yang sudah terdaftar</p>
                        </div>
                    </div>
                    
                    {{-- Form Pencarian Pasien --}}
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 border-2 border-blue-300 p-6 rounded-xl shadow-md">
                        <div class="flex items-center mb-4">
                            <i class="bi bi-search text-blue-700 text-2xl mr-3"></i>
                            <h3 class="font-bold text-blue-900 text-lg">Cari Data Pasien</h3>
                        </div>
                        
                        <form @submit.prevent="searchPatient()" class="flex flex-col sm:flex-row gap-3">
                            <input type="text" 
                                x-model="patientSearchTerm" 
                                @keydown.enter.prevent="searchPatient()"
                                placeholder="Masukkan NIK atau Nama Pasien" 
                                class="flex-grow px-4 py-3 border-2 border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <button type="submit" 
                                :disabled="isSearching || patientSearchTerm.length < 3"
                                class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 disabled:from-gray-400 disabled:to-gray-500 text-white font-bold py-3 px-8 rounded-lg transition-all duration-300 flex items-center justify-center shadow-md hover:shadow-lg whitespace-nowrap">
                                <i class="bi bi-search mr-2" x-show="!isSearching"></i>
                                <span x-show="!isSearching">Cari</span>
                                <i class="bi bi-arrow-repeat animate-spin mr-2" x-show="isSearching"></i>
                                <span x-show="isSearching">Mencari...</span>
                            </button>
                        </form>
                    </div>

                    {{-- Hasil: Pasien Ditemukan --}}
                    <div x-show="isPatientFound" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-300 p-6 rounded-xl shadow-lg">
                        
                        {{-- Info Pasien --}}
                        <div class="bg-white p-5 rounded-lg shadow-sm mb-6">
                            <div class="flex items-center justify-between mb-4 pb-3 border-b-2 border-green-200">
                                <div class="flex items-center">
                                    <i class="bi bi-person-check-fill text-green-600 text-3xl mr-3"></i>
                                    <h3 class="font-bold text-green-800 text-xl">Pasien Ditemukan</h3>
                                </div>
                                <span class="bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-bold">
                                    ID: <span x-text="patientData.id"></span>
                                </span>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-gray-700">
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Nama Lengkap</p>
                                    <p class="font-bold text-lg" x-text="patientData.name"></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Usia</p>
                                    <p class="font-semibold"><span x-text="patientData.age"></span> tahun</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">NIK</p>
                                    <p class="font-mono font-semibold" x-text="patientData.nik"></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Jenis Pembayaran (Terakhir)</p>
                                    <p class="font-semibold" x-text="patientData.payment"></p>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Form Kunjungan Baru --}}
                        <div class="bg-white p-6 rounded-lg shadow-sm">
                            <div class="flex items-center mb-5">
                                <i class="bi bi-calendar-plus text-yellow-600 text-2xl mr-3"></i>
                                <h3 class="text-xl font-bold text-gray-800">Daftarkan Kunjungan Baru</h3>
                            </div>

                            <form action="{{ route('petugas.pendaftaran.store.old') }}" method="POST" class="space-y-5">
                                @csrf
                                
                                <input type="hidden" name="patient_id" x-bind:value="patientData.id">
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label for="poli_tujuan_lama" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Poli Tujuan <span class="text-red-500">*</span>
                                        </label>
                                        <select id="poli_tujuan_lama" name="poli_tujuan_lama" required
                                            x-model="selectedClinicOld"
                                            @change="loadDoctors('old')"
                                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all">
                                            <option value="">Pilih Poli</option>
                                            <template x-for="clinic in allClinics" :key="clinic.id">
                                                <option :value="clinic.id" x-text="clinic.nama_poli"></option>
                                            </template>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label for="dokter_tujuan_lama" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Dokter Tujuan <span class="text-red-500">*</span>
                                        </label>
                                        <select id="dokter_tujuan_lama" name="dokter_tujuan_lama" required
                                            x-model="selectedDoctorOld"
                                            :disabled="isLoadingDoctors || doctors.length === 0"
                                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all disabled:bg-gray-100">

                                            <option value="" x-show="selectedClinicOld === ''">Pilih Poli Terlebih Dahulu</option>
                                            <option value="" x-show="isLoadingDoctors">Memuat dokter...</option>
                                            <option value="" x-show="selectedClinicOld !== '' && !isLoadingDoctors && doctors.length === 0">Tidak ada dokter di poli ini</option>
                                            <option value="" x-show="selectedClinicOld !== '' && !isLoadingDoctors && doctors.length > 0">Pilih Dokter</option>

                                            <template x-for="doctor in doctors" :key="doctor.id">
                                                <option :value="doctor.id" x-text="doctor.name"></option>
                                            </template>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label for="tanggal_kunjungan_lama" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Tanggal Kunjungan <span class="text-red-500">*</span>
                                        </label>
                                        <input type="date" id="tanggal_kunjungan_lama" name="tanggal_kunjungan_lama" required
                                            value="{{ now()->format('Y-m-d') }}" 
                                            min="{{ now()->format('Y-m-d') }}"
                                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all">
                                    </div>

                                    <div class="md:col-span-2">
                                        <label for="keluhan_lama" class="block text-sm font-semibold text-gray-700 mb-2">
                                            Keluhan Saat Ini <span class="text-red-500">*</span>
                                        </label>
                                        <textarea id="keluhan_lama" name="keluhan_lama" rows="3" required
                                            placeholder="Jelaskan keluhan atau alasan kunjungan"
                                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all"></textarea>
                                    </div>
                                </div>

                                <div class="pt-4">
                                    <button type="submit" 
                                        class="w-full bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-300 flex items-center justify-center shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                        <i class="bi bi-calendar-plus-fill mr-3 text-xl"></i> 
                                        DAFTARKAN KUNJUNGAN BARU
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Hasil: Pasien Tidak Ditemukan --}}
                    <div x-show="!isPatientFound && patientSearchTerm.length > 0 && !isSearching" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="bg-red-50 border-2 border-red-300 p-6 rounded-xl shadow-md">
                        <div class="flex items-start">
                            <i class="bi bi-exclamation-octagon-fill text-red-500 text-3xl mr-4"></i>
                            <div>
                                <p class="font-bold text-red-800 text-lg mb-2">Pasien Tidak Ditemukan</p>
                                <p class="text-red-700 mb-4">Data pasien dengan NIK/Nama '<span x-text="patientSearchTerm"></span>' tidak ada di sistem.</p>
                                <button @click="activeTab = 'new_patient'; resetSearch();"
                                    class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-6 rounded-lg transition-all duration-300 flex items-center shadow-md">
                                    <i class="bi bi-person-plus-fill mr-2"></i>
                                    Daftarkan sebagai Pasien Baru
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
        
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }
    </style>

@endsection