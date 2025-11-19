{{-- resources/views/dokter/antrian.blade.php --}}

@extends('layouts.app')

@section('title', 'Antrian Pemeriksaan Hari Ini')

@section('content')

<div class="space-y-6">
    
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center">
            <i class="bi bi-person-lines-fill text-primary mr-3"></i> 
            Antrian Pemeriksaan Hari Ini
        </h1>
        
        <a href="{{ route('dokter.pasien') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-5 rounded-xl transition-all duration-300 flex items-center shadow-md hover:shadow-lg">
            <i class="bi bi-people-fill mr-2"></i> 
            Kembali ke Pasien Saya
        </a>
    </div>

    {{-- Info Tanggal --}}
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
        <p class="text-gray-700 flex items-center">
            <i class="bi bi-calendar-date mr-2 text-blue-600"></i>
            <span class="font-medium">{{ \Carbon\Carbon::today()->isoFormat('dddd, D MMMM YYYY') }}</span>
        </p>
    </div>

    {{-- Notifikasi Sukses --}}
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-xl shadow-md animate-fade-in" role="alert">
            <div class="flex items-center">
                <i class="bi bi-check-circle-fill mr-3 text-xl"></i>
                <div>
                    <strong class="font-bold">Berhasil!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            </div>
        </div>
    @endif
    
    {{-- Tabel Antrian --}}
    <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
        
        {{-- Header Tabel --}}
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white flex items-center">
                <i class="bi bi-list-ol mr-2"></i>
                Daftar Antrian Pasien
            </h2>
        </div>

        {{-- Tabel Content --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-16">
                            No.
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-24">
                            <i class="bi bi-clock mr-1"></i> Jam
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <i class="bi bi-person mr-1"></i> Nama Pasien
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden sm:table-cell">
                            <i class="bi bi-hospital mr-1"></i> Poli
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden md:table-cell">
                            <i class="bi bi-chat-left-text mr-1"></i> Keluhan Awal
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-36">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    
                    @forelse($appointments as $appointment)
                    <tr class="hover:bg-blue-50 transition-colors duration-150">
                        
                        {{-- Nomor Urut --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-700 rounded-full font-bold text-sm">
                                {{ $loop->iteration }}
                            </div>
                        </td>
                        
                        {{-- Jam Kunjungan --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <i class="bi bi-clock-fill text-blue-500 mr-2"></i>
                                <span class="text-sm font-semibold text-gray-900">
                                    {{ \Carbon\Carbon::parse($appointment->jam_kunjungan)->format('H:i') }}
                                </span>
                            </div>
                        </td>
                        
                        {{-- Nama Pasien --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ substr($appointment->patient->user->name ?? 'P', 0, 1) }}
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-semibold text-gray-900">
                                        {{ $appointment->patient->user->name ?? 'Pasien Dihapus' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        ID: {{ 'P' . str_pad($appointment->patient->id, 4, '0', STR_PAD_LEFT) }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        
                        {{-- Poli --}}
                        <td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                {{ $appointment->clinic->nama_poli }}
                            </span>
                        </td>
                        
                        {{-- Keluhan Awal --}}
                        <td class="px-6 py-4 hidden md:table-cell">
                            <div class="text-sm text-gray-600 max-w-xs">
                                {{ \Illuminate\Support\Str::limit($appointment->keluhan, 60) }}
                            </div>
                        </td>
                        
                        {{-- Tombol Aksi --}}
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <button 
                                type="button"
                                data-record="{{ json_encode([
                                    'patient_id' => $appointment->patient_id,
                                    'patient_name' => $appointment->patient->user->name ?? 'N/A',
                                    'doctor_id' => $doctor->id,
                                    'clinic_id' => $appointment->clinic_id, 
                                    'appointment_id' => $appointment->id,    
                                    'keluhan_awal' => $appointment->keluhan,
                                ]) }}"
                                onclick="openPeriksaModal(this)"
                                title="Mulai Pemeriksaan Pasien" 
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white text-sm font-semibold rounded-lg transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <i class="bi bi-stethoscope mr-2"></i> 
                                Mulai Periksa
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <i class="bi bi-inbox text-6xl mb-4"></i>
                                <p class="text-lg font-medium text-gray-500">
                                    Tidak ada antrian pasien hari ini
                                </p>
                                <p class="text-sm text-gray-400 mt-2">
                                    Belum ada janji temu yang menunggu atau disetujui
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                    
                </tbody>
            </table>
        </div>
        
        {{-- Footer: Pagination dan Info --}}
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="flex items-center text-sm text-gray-600">
                    <i class="bi bi-people-fill mr-2 text-blue-500"></i>
                    <span class="font-medium">Total antrian hari ini: </span>
                    <span class="ml-1 px-2 py-1 bg-blue-100 text-blue-700 rounded-full font-bold text-xs">
                        {{ $appointments->total() }}
                    </span>
                </div>
                <div>
                    {{ $appointments->links('pagination::tailwind') }} 
                </div>
            </div>
        </div>

    </div>

</div>

{{-- INCLUDE MODAL REKAM MEDIS --}}
@include('dokter.partials.modal_rekam_medis', ['doctor' => $doctor])

{{-- SCRIPT JAVASCRIPT --}}
<script>
    // --- FUNGSI UTILITY ---
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
            
            const form = modal.querySelector('form');
            if (form) {
                @if (!$errors->any())
                    form.reset();
                @endif
            }
        }
    }
    
    // --- HANDLER MODAL PERIKSA ---
    function openPeriksaModal(buttonElement) {
        try {
            const dataString = buttonElement.getAttribute('data-record');
            const data = JSON.parse(dataString);
            
            // Set nama pasien di header modal
            const modalPatientName = document.getElementById('modal_patient_name');
            if (modalPatientName) {
                modalPatientName.textContent = data.patient_name;
            }
            
            // Set ID pasien dan Appointment
            const patientIdInput = document.getElementById('rm_patient_id');
            const appointmentIdInput = document.getElementById('rm_appointment_id');
            
            if (patientIdInput) patientIdInput.value = data.patient_id;
            if (appointmentIdInput) appointmentIdInput.value = data.appointment_id || '';

            // Set Poli Otomatis
            const clinicSelect = document.querySelector('#formNewRecord select[name="clinic_id"]');
            if (clinicSelect && data.clinic_id) {
                clinicSelect.value = data.clinic_id;
            }

            // Set Keluhan Awal
            const keluhanTextarea = document.querySelector('#formNewRecord textarea[name="keluhan"]');
            if (keluhanTextarea) {
                keluhanTextarea.value = data.keluhan_awal || '';
            }
            
            // Reset input lain
            const diagnosaTextarea = document.querySelector('#formNewRecord textarea[name="diagnosa"]');
            const tindakanTextarea = document.querySelector('#formNewRecord textarea[name="tindakan"]');
            const catatanTextarea = document.querySelector('#formNewRecord textarea[name="catatan_dokter"]');
            
            if (diagnosaTextarea) diagnosaTextarea.value = '';
            if (tindakanTextarea) tindakanTextarea.value = '';
            if (catatanTextarea) catatanTextarea.value = '';

            openModal('modalNewRecord');
        } catch (error) {
            console.error('Error opening modal:', error);
            alert('Terjadi kesalahan saat membuka form pemeriksaan');
        }
    }
    
    // --- DOM CONTENT LOADED ---
    document.addEventListener('DOMContentLoaded', function() {
        
        // Auto-open modal jika ada error validasi
        @if ($errors->any() && old('patient_id'))
            const patientId = "{{ old('patient_id') }}";
            const appointmentId = "{{ old('appointment_id') }}";
            
            const patientIdInput = document.getElementById('rm_patient_id');
            const appointmentIdInput = document.getElementById('rm_appointment_id');
            const modalPatientName = document.getElementById('modal_patient_name');
            
            if (patientIdInput) patientIdInput.value = patientId;
            if (appointmentIdInput) appointmentIdInput.value = appointmentId;
            if (modalPatientName) modalPatientName.textContent = 'Pasien ID ' + patientId;
            
            const oldClinic = "{{ old('clinic_id') }}";
            if (oldClinic) {
                const select = document.querySelector('#formNewRecord select[name="clinic_id"]');
                if (select) select.value = oldClinic;
            }
            
            openModal('modalNewRecord');
        @endif

        // Close modal by clicking outside
        document.querySelectorAll('[id^="modal"]').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeModal(this.id);
                }
            });
        });
        
        // Close modal by ESC key
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

<style>
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