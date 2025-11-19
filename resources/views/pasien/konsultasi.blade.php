@extends('layouts.app')

@section('title', 'Pendaftaran Konsultasi')

@section('content')

    <div class="space-y-6 max-w-4xl mx-auto">
        
        {{-- HEADER --}}
        <header class="bg-white p-6 rounded-2xl shadow-lg border-l-8 border-blue-600">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center mb-1">
                <i class="bi bi-hospital text-blue-600 mr-3"></i> Pendaftaran Rawat Jalan
            </h1>
            <p class="text-gray-600">Silakan isi formulir di bawah untuk membuat janji temu dengan dokter.</p>
        </header>
        
        {{-- ALERTS --}}
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm" role="alert">
                <p class="font-bold">Berhasil!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm" role="alert">
                <p class="font-bold">Perhatian!</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        {{-- STATUS JANJI TEMU AKTIF --}}
        @if ($activeAppointment)
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 shadow-md flex flex-col sm:flex-row justify-between items-center gap-4">
                <div>
                    <h3 class="font-bold text-blue-800 text-lg mb-1"><i class="bi bi-ticket-perforated mr-2"></i>Tiket Antrian Aktif</h3>
                    <div class="text-sm text-blue-700 space-y-1">
                        <p><strong>Poli:</strong> {{ $activeAppointment->clinic->nama_poli }}</p>
                        <p><strong>Dokter:</strong> {{ $activeAppointment->doctor->user->name }}</p>
                        <p><strong>Waktu:</strong> {{ \Carbon\Carbon::parse($activeAppointment->tanggal_kunjungan)->format('d M Y') }} - {{ $activeAppointment->jam_kunjungan }}</p>
                        <p><strong>Status:</strong> <span class="uppercase font-bold bg-blue-200 px-2 py-0.5 rounded text-xs">{{ $activeAppointment->status }}</span></p>
                    </div>
                </div>
                <button class="px-5 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition shadow disabled:opacity-50" disabled>
                    Sedang Menunggu
                </button>
            </div>
        @else

            {{-- FORM PENDAFTARAN --}}
            <div class="bg-white shadow-xl rounded-2xl border border-gray-100 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-700">Formulir Janji Temu</h2>
                </div>
                
                <div class="p-6">
                    <form action="{{ route('pasien.konsultasi.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            {{-- 1. PILIH POLI --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">1. Pilih Poli Tujuan</label>
                                <select name="clinic_id" id="clinic_id" required
                                    class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-white">
                                    <option value="">-- Pilih Poli --</option>
                                    @foreach ($clinics as $clinic)
                                        <option value="{{ $clinic->id }}">{{ $clinic->nama_poli }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- 2. PILIH DOKTER (Dinamis) --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">2. Pilih Dokter</label>
                                <select name="doctor_id" id="doctor_id" required disabled
                                    class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-gray-100 disabled:text-gray-400">
                                    <option value="">-- Pilih Poli Terlebih Dahulu --</option>
                                </select>
                                <p id="loading-dokter" class="hidden text-xs text-blue-500 mt-1 animate-pulse">Sedang memuat dokter...</p>
                            </div>
                        </div>

                        {{-- INFO JADWAL DOKTER --}}
                        <div id="info-jadwal-container" class="hidden bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-info-circle-fill text-yellow-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700 font-bold">Jadwal Praktek Dokter:</p>
                                    <p class="text-sm text-yellow-700 mt-1" id="text-jadwal">-</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- 3. TANGGAL --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">3. Rencana Tanggal</label>
                                <input type="date" name="tanggal_kunjungan" id="tanggal_kunjungan" required
                                    class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 transition-all">
                            </div>

                            {{-- 4. JAM (Dinamis dari Controller) --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">4. Pilih Jam Kunjungan</label>
                                <select name="jam_kunjungan" id="jam_kunjungan" required disabled
                                    class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 transition-all bg-gray-100">
                                    <option value="">-- Pilih Dokter Terlebih Dahulu --</option>
                                </select>
                            </div>
                        </div>
                        
                        {{-- KELUHAN --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Keluhan Utama</label>
                            <textarea name="keluhan" rows="3" placeholder="Jelaskan keluhan singkat yang Anda rasakan..."
                                class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 transition-all"></textarea>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl transition-all shadow-lg hover:shadow-xl flex items-center justify-center">
                                <i class="bi bi-send-check-fill mr-2"></i> Buat Janji Temu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>

    {{-- JAVASCRIPT UNTUK DROPDOWN BERTINGKAT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const clinicSelect = document.getElementById('clinic_id');
            const doctorSelect = document.getElementById('doctor_id');
            const jamSelect = document.getElementById('jam_kunjungan');
            const loadingDokter = document.getElementById('loading-dokter');
            const infoJadwal = document.getElementById('info-jadwal-container');
            const textJadwal = document.getElementById('text-jadwal');

            // 1. Event Saat Poli Dipilih
            clinicSelect.addEventListener('change', function() {
                const clinicId = this.value;
                
                // Reset Dokter & Jam
                doctorSelect.innerHTML = '<option value="">-- Pilih Dokter --</option>';
                doctorSelect.disabled = true;
                doctorSelect.classList.add('bg-gray-100');
                
                jamSelect.innerHTML = '<option value="">-- Pilih Dokter Terlebih Dahulu --</option>';
                jamSelect.disabled = true;
                
                infoJadwal.classList.add('hidden');

                if (clinicId) {
                    loadingDokter.classList.remove('hidden');
                    
                    // Fetch ke API Controller
                    fetch(`/pasien/konsultasi/get-doctors/${clinicId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.length > 0) {
                                data.forEach(doctor => {
                                    // Tampilkan Nama + Spesialis
                                    const option = document.createElement('option');
                                    option.value = doctor.id;
                                    option.textContent = `${doctor.user.name} (${doctor.spesialis || '-'})`;
                                    doctorSelect.appendChild(option);
                                });
                                doctorSelect.disabled = false;
                                doctorSelect.classList.remove('bg-gray-100');
                            } else {
                                doctorSelect.innerHTML = '<option value="">-- Tidak ada dokter tersedia --</option>';
                            }
                        })
                        .catch(err => console.error(Error, err))
                        .finally(() => {
                            loadingDokter.classList.add('hidden');
                        });
                }
            });

            // 2. Event Saat Dokter Dipilih
            doctorSelect.addEventListener('change', function() {
                const doctorId = this.value;

                // Reset Jam
                jamSelect.innerHTML = '<option value="">-- Pilih Jam --</option>';
                jamSelect.disabled = true;
                jamSelect.classList.add('bg-gray-100');
                
                infoJadwal.classList.add('hidden');

                if (doctorId) {
                    // Fetch ke API Controller
                    fetch(`/pasien/konsultasi/get-schedule/${doctorId}`)
                        .then(response => response.json())
                        .then(data => {
                            // Update Info Jadwal Teks
                            textJadwal.textContent = data.jadwal_teks;
                            infoJadwal.classList.remove('hidden');

                            // Update Dropdown Slot Waktu
                            if (data.slots && data.slots.length > 0) {
                                data.slots.forEach(time => {
                                    const option = document.createElement('option');
                                    option.value = time; // Simpan format HH:mm
                                    option.textContent = time + ' WIB';
                                    jamSelect.appendChild(option);
                                });
                                jamSelect.disabled = false;
                                jamSelect.classList.remove('bg-gray-100');
                            } else {
                                jamSelect.innerHTML = '<option value="">-- Jadwal Penuh / Libur --</option>';
                            }
                        })
                        .catch(err => console.error(Error, err));
                }
            });
        });
    </script>
@endsection