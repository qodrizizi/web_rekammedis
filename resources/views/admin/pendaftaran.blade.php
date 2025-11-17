{{-- resources/views/admin/pendaftaran.blade.php --}}

@extends('layouts.app')

@section('title', 'Data Pendaftaran Pasien')

@section('content')

    <div class="space-y-6">
        
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <i class="bi bi-calendar-check-fill text-primary mr-3"></i> Data Pendaftaran & Janji Temu
            </h1>
            
            <button onclick="openModal('modalAddAppointment')" class="bg-primary hover:bg-secondary text-white font-semibold py-2 px-5 rounded-xl transition-colors duration-300 flex items-center shadow-md hover:shadow-lg">
                <i class="bi bi-calendar-plus-fill mr-2"></i> Buat Janji Temu Baru
            </button>
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


        <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-100">
            
            <form method="GET" action="{{ route('admin.pendaftaran') }}" class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0">
                
                <div class="w-full md:w-1/3">
                    <div class="relative">
                        <input type="text" name="search" placeholder="Cari berdasarkan Pasien, Dokter, atau Poli..." 
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                            aria-label="Cari Pendaftaran" value="{{ request('search') }}">
                        <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    
                    <select name="status" onchange="this.form.submit()" class="p-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-colors">
                        <option value="">Semua Status (Default: Aktif)</option>
                        @foreach($statuses as $s)
                            <option value="{{ $s }}" @selected(request('status') == $s)>Status: {{ ucfirst($s) }}</option>
                        @endforeach
                    </select>

                    <input type="date" name="date" onchange="this.form.submit()" 
                        class="p-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-primary transition-colors" 
                        value="{{ request('date') ?? Carbon\Carbon::now()->format('Y-m-d') }}">
                </div>
            </form>

            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-12">
                                ID
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Tanggal & Waktu
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden sm:table-cell">
                                Pasien
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden lg:table-cell">
                                Dokter & Poli
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-32">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        
                        @forelse($appointments as $appointment)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #{{ $appointment->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::parse($appointment->tanggal_kunjungan)->isoFormat('D MMM YYYY') }}</p>
                                <p class="text-xs text-gray-500">{{ $appointment->jam_kunjungan ? \Carbon\Carbon::parse($appointment->jam_kunjungan)->format('H:i') . ' WIB' : 'Waktu Belum Diset' }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 hidden sm:table-cell">
                                <p>{{ $appointment->patient->user->name ?? 'Pasien Dihapus' }}</p>
                                <p class="text-xs text-gray-500 italic truncate w-40" title="{{ $appointment->keluhan }}">Keluhan: {{ $appointment->keluhan }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden lg:table-cell">
                                <div class="font-medium text-gray-800">{{ $appointment->doctor->user->name ?? 'Dokter Dihapus' }}</div>
                                <div class="text-xs text-primary">{{ $appointment->clinic->nama_poli ?? 'Poli Dihapus' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                @php
                                    $status = $appointment->status;
                                    $statusClass = [
                                        'menunggu' => 'bg-yellow-100 text-yellow-800',
                                        'disetujui' => 'bg-blue-100 text-blue-800',
                                        'selesai' => 'bg-green-100 text-green-800',
                                        'batal' => 'bg-red-100 text-red-800',
                                    ][$status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="{{ $statusClass }} px-3 py-1 rounded-full text-xs font-semibold capitalize">
                                    {{ $status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-center space-x-1.5">
                                    
                                    {{-- Data JSON Janji Temu --}}
                                    @php
                                        $dataJson = [
                                            'id' => $appointment->id,
                                            'patient_name' => $appointment->patient->user->name ?? 'N/A',
                                            'doctor_name' => $appointment->doctor->user->name ?? 'N/A',
                                            'clinic_name' => $appointment->clinic->nama_poli ?? 'N/A',
                                            'patient_id' => $appointment->patient_id,
                                            'doctor_id' => $appointment->doctor_id,
                                            'clinic_id' => $appointment->clinic_id,
                                            'tanggal_kunjungan' => $appointment->tanggal_kunjungan,
                                            'jam_kunjungan' => $appointment->jam_kunjungan,
                                            'keluhan' => $appointment->keluhan,
                                            'status' => $appointment->status,
                                        ];
                                    @endphp

                                    {{-- Tombol Lihat Detail/Keluhan (Memanggil Modal View) --}}
                                    <button 
                                        type="button"
                                        data-appointment='@json($dataJson)'
                                        onclick="viewAppointmentFromData(this)" 
                                        title="Lihat Detail Janji Temu" 
                                        class="text-primary hover:text-secondary p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-info-circle text-lg"></i>
                                    </button>
                                    
                                    {{-- Tombol Ubah Status (Memanggil Modal Edit Cepat) --}}
                                    <button 
                                        type="button"
                                        data-appointment='@json($dataJson)'
                                        onclick="editAppointmentFromData(this)" 
                                        title="Ubah Status / Edit" 
                                        class="text-indigo-600 hover:text-indigo-800 p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-arrow-repeat text-lg"></i>
                                    </button>
                                    
                                    {{-- Tombol Batalkan (Memanggil Modal Delete) --}}
                                    <button onclick='deleteAppointment("{{ $appointment->id }}", "Janji Temu #{{ $appointment->id }}")' title="Batalkan Janji" class="text-red-600 hover:text-red-800 p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                        <i class="bi bi-x-circle text-lg"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                Tidak ada data pendaftaran/janji temu ditemukan.
                            </td>
                        </tr>
                        @endforelse
                        
                    </tbody>
                </table>
            </div>
            
            <div class="mt-6 flex justify-between items-center">
                <p class="text-sm text-gray-600 hidden sm:block">Menampilkan {{ $appointments->firstItem() ?? 0 }} - {{ $appointments->lastItem() ?? 0 }} dari {{ $appointments->total() }} Janji Temu</p>
                <div class="flex space-x-2 ml-auto">
                    {{ $appointments->links('pagination::tailwind') }} 
                </div>
            </div>

        </div>

    </div>

    {{-- MODALS SECTION --}}

    {{-- Modal Tambah Janji Temu Baru (Mirip Modal Add Obat) --}}
    <div id="modalAddAppointment" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 rounded-t-2xl">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="bi bi-calendar-plus-fill text-primary mr-3"></i> Buat Janji Temu Baru
                    </h2>
                    <button type="button" onclick="closeModal('modalAddAppointment')" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="bi bi-x-lg text-2xl"></i>
                    </button>
                </div>
            </div>
            
            <form id="formAddAppointment" action="{{ route('admin.pendaftaran.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Pasien <span class="text-red-500">*</span></label>
                        <select name="patient_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                            <option value="">-- Pilih Pasien --</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient['id'] }}" @selected(old('patient_id') == $patient['id'])>{{ $patient['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Dokter Tujuan <span class="text-red-500">*</span></label>
                        <select name="doctor_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                            <option value="">-- Pilih Dokter --</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor['id'] }}" @selected(old('doctor_id') == $doctor['id'])>{{ $doctor['name'] }} ({{ $doctor['spesialis'] }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Poli Kunjungan <span class="text-red-500">*</span></label>
                        <select name="clinic_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                            <option value="">-- Pilih Poli --</option>
                            @foreach($clinics as $clinic)
                                <option value="{{ $clinic->id }}" @selected(old('clinic_id') == $clinic->id)>{{ $clinic->nama_poli }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Kunjungan <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_kunjungan" required value="{{ old('tanggal_kunjungan') ?? \Carbon\Carbon::now()->format('Y-m-d') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jam Kunjungan (Opsional)</label>
                    <input type="time" name="jam_kunjungan" value="{{ old('jam_kunjungan') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Keluhan Utama <span class="text-red-500">*</span></label>
                    <textarea name="keluhan" rows="3" placeholder="Keluhan yang dirasakan pasien" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">{{ old('keluhan') }}</textarea>
                </div>


                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModal('modalAddAppointment')"
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-semibold">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-6 py-2 bg-primary text-white rounded-xl hover:bg-secondary transition-colors font-semibold shadow-md hover:shadow-lg">
                        <i class="bi bi-check-lg mr-2"></i> Ajukan Janji Temu
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit/Update Status Janji Temu --}}
    <div id="modalEditAppointment" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 rounded-t-2xl">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="bi bi-pencil-square text-yellow-600 mr-3"></i> Edit Janji Temu <span id="edit_appointment_id"></span>
                    </h2>
                    <button type="button" onclick="closeModal('modalEditAppointment')" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="bi bi-x-lg text-2xl"></i>
                    </button>
                </div>
            </div>
            
            <form id="formEditAppointment" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT') 
                
                <input type="hidden" id="edit_id" name="id"> 
                
                <h3 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Ubah Status & Data Kunjungan</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Status Janji Temu <span class="text-red-500">*</span></label>
                        <select id="edit_status" name="status" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                            @foreach(['menunggu', 'disetujui', 'selesai', 'batal'] as $s)
                                <option value="{{ $s }}">{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Kunjungan <span class="text-red-500">*</span></label>
                        <input type="date" id="edit_tanggal_kunjungan" name="tanggal_kunjungan" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jam Kunjungan (Opsional)</label>
                        <input type="time" id="edit_jam_kunjungan" name="jam_kunjungan"
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Poli Kunjungan <span class="text-red-500">*</span></label>
                        <select id="edit_clinic_id" name="clinic_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                            <option value="">-- Pilih Poli --</option>
                            @foreach($clinics as $clinic)
                                <option value="{{ $clinic->id }}">{{ $clinic->nama_poli }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Pasien <span class="text-red-500">*</span></label>
                        <select id="edit_patient_id" name="patient_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                            <option value="">-- Pilih Pasien --</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient['id'] }}">{{ $patient['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Dokter Tujuan <span class="text-red-500">*</span></label>
                        <select id="edit_doctor_id" name="doctor_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                            <option value="">-- Pilih Dokter --</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor['id'] }}">{{ $doctor['name'] }} ({{ $doctor['spesialis'] }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Keluhan Utama <span class="text-red-500">*</span></label>
                    <textarea id="edit_keluhan" name="keluhan" rows="3" placeholder="Keluhan yang dirasakan pasien" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all"></textarea>
                </div>


                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModal('modalEditAppointment')"
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-semibold">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-6 py-2 bg-yellow-600 text-white rounded-xl hover:bg-yellow-700 transition-colors font-semibold shadow-md hover:shadow-lg">
                        <i class="bi bi-check-lg mr-2"></i> Update Janji Temu
                    </button>
                </div>
            </form>
        </div>
    </div>


    {{-- Modal View Detail Janji Temu --}}
    <div id="modalViewAppointment" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 rounded-t-2xl">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="bi bi-info-circle-fill text-primary mr-3"></i> Detail Janji Temu <span id="view_appointment_id"></span>
                    </h2>
                    <button type="button" onclick="closeModal('modalViewAppointment')" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="bi bi-x-lg text-2xl"></i>
                    </button>
                </div>
            </div>
            
            <div class="p-6 space-y-6">
                
                <div class="bg-gradient-to-r from-primary/10 to-secondary/10 rounded-xl p-5">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Tanggal Kunjungan</p>
                            <p id="view_date_time" class="text-2xl font-bold text-primary">-</p>
                        </div>
                        <span id="view_status_badge" class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold capitalize">
                            -
                        </span>
                    </div>
                    <div class="mt-4">
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Pasien</p>
                        <p id="view_patient_name" class="text-xl font-bold text-gray-800">-</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="border border-gray-200 rounded-xl p-4">
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Dokter Tujuan</p>
                        <p id="view_doctor_name" class="text-lg font-bold text-gray-800">-</p>
                    </div>

                    <div class="border border-gray-200 rounded-xl p-4">
                        <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Poli</p>
                        <p id="view_clinic_name" class="text-lg font-bold text-primary">-</p>
                    </div>
                </div>
                
                <div class="border border-gray-200 rounded-xl p-4">
                    <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Keluhan Utama</p>
                    <p id="view_keluhan" class="text-sm text-gray-700 leading-relaxed italic">-</p>
                </div>
                
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModal('modalViewAppointment')"
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-semibold">
                        Tutup
                    </button>
                    <button type="button" id="btnEditFromView"
                        class="px-6 py-2 bg-yellow-600 text-white rounded-xl hover:bg-yellow-700 transition-colors font-semibold shadow-md hover:shadow-lg">
                        <i class="bi bi-arrow-repeat mr-2"></i> Ubah Status/Edit
                    </button>
                </div>
            </div>
        </div>
    </div>


    {{-- Modal Konfirmasi Batal Janji Temu --}}
    <div id="modalDeleteAppointment" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
            <div class="p-6">
                <div class="flex items-center justify-center w-16 h-16 mx-auto bg-red-100 rounded-full mb-4">
                    <i class="bi bi-x-circle-fill text-red-600 text-3xl"></i>
                </div>
                
                <h3 class="text-xl font-bold text-gray-800 text-center mb-2">Batalkan Janji Temu</h3>
                <p class="text-gray-600 text-center mb-1">Anda akan membatalkan dan menghapus Janji Temu:</p>
                <p id="delete_appointment_info" class="text-center font-bold text-gray-800 mb-4">-</p>
                <p class="text-sm text-red-600 text-center mb-6">Tindakan ini tidak dapat dibatalkan!</p>
                
                <form id="formDeleteAppointment" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <div class="flex space-x-3">
                        <button type="button" onclick="closeModal('modalDeleteAppointment')"
                            class="flex-1 px-6 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-semibold">
                            Tutup
                        </button>
                        <button type="submit"
                            class="flex-1 px-6 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors font-semibold shadow-md hover:shadow-lg">
                            <i class="bi bi-x-circle mr-2"></i> Batalkan & Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script JavaScript (Disesuaikan untuk Janji Temu) --}}
    <script>
        let currentAppointmentData = null;

        // --- FUNGSI UTILITY ---
        const STATUS_CLASSES = {
            'menunggu': 'bg-yellow-100 text-yellow-800',
            'disetujui': 'bg-blue-100 text-blue-800',
            'selesai': 'bg-green-100 text-green-800',
            'batal': 'bg-red-100 text-red-800',
        };

        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';

            @if ($errors->any() && old('patient_id'))
                if (modalId === 'modalAddAppointment') {
                    // Cukup buka modal jika ada error validasi
                }
            @endif
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
            const form = modal.querySelector('form');
            if (form && modalId === 'modalAddAppointment') {
                 @if (!$errors->any())
                    form.reset();
                @endif
            }
        }
        
        function formatDateIndo(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            const options = { day: 'numeric', month: 'long', year: 'numeric' };
            return date.toLocaleDateString('id-ID', options);
        }

        // --- HANDLERS DARI TOMBOL ---
        function viewAppointmentFromData(buttonElement) {
            const dataString = buttonElement.getAttribute('data-appointment');
            const data = JSON.parse(dataString);
            viewAppointment(data);
        }
        
        function editAppointmentFromData(buttonElement) {
            const dataString = buttonElement.getAttribute('data-appointment');
            const data = JSON.parse(dataString);
            editAppointment(data);
        }

        // 1. View Appointment (Logika utama)
        function viewAppointment(data) {
            currentAppointmentData = data;
            
            document.getElementById('view_appointment_id').textContent = '#' + data.id;
            document.getElementById('view_patient_name').textContent = data.patient_name;
            document.getElementById('view_doctor_name').textContent = data.doctor_name;
            document.getElementById('view_clinic_name').textContent = data.clinic_name;
            document.getElementById('view_keluhan').textContent = data.keluhan;
            
            // Tanggal dan Waktu
            const date = formatDateIndo(data.tanggal_kunjungan);
            const time = data.jam_kunjungan ? data.jam_kunjungan.substring(0, 5) + ' WIB' : 'Waktu Belum Diset';
            document.getElementById('view_date_time').textContent = date + ' (' + time + ')';

            // Status Badge
            const statusBadge = document.getElementById('view_status_badge');
            const statusClass = STATUS_CLASSES[data.status] || STATUS_CLASSES['menunggu'];
            statusBadge.className = statusClass + ' px-3 py-1 rounded-full text-xs font-semibold capitalize';
            statusBadge.textContent = data.status;
            
            document.getElementById('btnEditFromView').onclick = function() {
                closeModal('modalViewAppointment');
                editAppointment(currentAppointmentData);
            };

            openModal('modalViewAppointment');
        }

        // 2. Edit Appointment (Logika utama)
        function editAppointment(data) {
            currentAppointmentData = data;
            
            const form = document.getElementById('formEditAppointment');
            form.action = "{{ url('admin/pendaftaran') }}/" + data.id; 
            
            document.getElementById('edit_appointment_id').textContent = '#' + data.id;
            document.getElementById('edit_patient_id').value = data.patient_id;
            document.getElementById('edit_doctor_id').value = data.doctor_id;
            document.getElementById('edit_clinic_id').value = data.clinic_id;
            document.getElementById('edit_tanggal_kunjungan').value = data.tanggal_kunjungan;
            document.getElementById('edit_jam_kunjungan').value = data.jam_kunjungan ? data.jam_kunjungan.substring(0, 5) : '';
            document.getElementById('edit_keluhan').value = data.keluhan;
            document.getElementById('edit_status').value = data.status;
            
            openModal('modalEditAppointment');
        }

        // 3. Delete Appointment (Konfirmasi Batal)
        function deleteAppointment(id, info) {
            document.getElementById('delete_appointment_info').textContent = info;
            
            const form = document.getElementById('formDeleteAppointment');
            form.action = "{{ url('admin/pendaftaran') }}/" + id; 
            
            openModal('modalDeleteAppointment');
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

            // LOGIC MENAMPILKAN MODAL TAMBAH JIKA ADA VALIDASI ERROR
            @if ($errors->any() && old('patient_id'))
                openModal('modalAddAppointment');
            @endif
        });
    </script>

@endsection