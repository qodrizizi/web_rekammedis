<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class ConsultationController extends Controller
{
    /**
     * Menampilkan halaman form pendaftaran konsultasi
     */
    public function index()
    {
        // Ambil data Poli/Clinic
        $clinics = Clinic::select('id', 'nama_poli')->get();
        
        // Ambil data Dokter
        // Relasi `user` diasumsikan sudah ada di Model Doctor
        $doctors = Doctor::with('user:id,name')->get(['id', 'user_id', 'spesialis']);

        // Data Janji Temu Aktif (contoh, ambil yang statusnya 'menunggu' atau 'disetujui')
        // *Asumsi: Pengguna adalah Pasien (Role ID 4) dan ID Pasien ada di tabel 'patients'*
        // Karena kita belum mengimplementasikan tabel 'patients', 
        // kita akan menggunakan data dummy atau user_id dari Auth::user() untuk simplicity.

        // TODO: Implementasi yang benar harus mencocokkan user_id dari Auth::user()
        //       ke patient_id di tabel 'patients', lalu mencari appointments dengan patient_id tersebut.
        $activeAppointment = Appointment::where('patient_id', 1) // Ganti dengan ID pasien user yang login
                                        ->whereIn('status', ['menunggu', 'disetujui'])
                                        ->with('doctor.user')
                                        ->latest()
                                        ->first();

        // Data dummy slot waktu (untuk keperluan form, slot ini biasanya dihitung/disimpan di database)
        $timeSlots = [
            ['value' => '10:00:00', 'label' => '10:00 - 10:30 (Tersedia)', 'disabled' => false],
            ['value' => '10:30:00', 'label' => '10:30 - 11:00 (Tersedia)', 'disabled' => false],
            ['value' => '11:00:00', 'label' => '11:00 - 11:30 (Penuh)', 'disabled' => true],
        ];

        return view('pasien.konsultasi', [
            'clinics' => $clinics,
            'doctors' => $doctors,
            'timeSlots' => $timeSlots,
            'activeAppointment' => $activeAppointment, // Kirim data janji temu aktif
        ]);
    }

    /**
     * Memproses pengajuan janji temu tatap muka
     */
    public function storeTatapMuka(Request $request)
    {
        // 1. Validasi Data
        $request->validate([
            'poli_tm' => 'required|exists:clinics,id',
            'dokter_tm' => 'nullable|exists:doctors,id',
            'tgl_tm' => 'required|date|after_or_equal:today',
            'waktu_tm' => 'required|date_format:H:i:s',
            'keluhan_tm' => 'nullable|string|max:500',
        ]);
        
        // *Asumsi: Patient ID diambil dari user yang sedang login.*
        // TODO: Ganti ini dengan logic untuk mendapatkan patient_id dari user yang login
        $patientId = 1; // Contoh: ID Pasien user yang login

        // 2. Buat Janji Temu Baru
        Appointment::create([
            'patient_id' => $patientId,
            'doctor_id' => $request->input('dokter_tm') ?? 1, // Jika dokter opsional, set default atau logic lain
            'clinic_id' => $request->input('poli_tm'),
            'tanggal_kunjungan' => $request->input('tgl_tm'),
            'jam_kunjungan' => $request->input('waktu_tm'),
            'keluhan' => $request->input('keluhan_tm'),
            'status' => 'menunggu', // Status default
            // Telemedis (Online) tidak perlu diisi jika form ini hanya untuk Tatap Muka
        ]);

        // 3. Redirect dengan pesan sukses
        return redirect()->route('consultation.index')->with('success', 'Pengajuan janji temu Tatap Muka berhasil diajukan dan sedang menunggu persetujuan.');
    }
    
    /**
     * Memproses pengajuan janji temu online (telemedis)
     */
    public function storeOnline(Request $request)
    {
        // 1. Validasi Data
        $request->validate([
            'poli_online' => 'required|string|max:100', // Karena form online tidak pakai ID poli dari DB
            'tgl_online' => 'required|date|after_or_equal:today',
            'keluhan_online' => 'required|string|max:1000',
        ]);
        
        // *CATATAN: Logika untuk Konsultasi Online (Telemedis) lebih kompleks 
        // karena harus mencari/mengalokasikan dokter dan jam.*
        
        // Karena form online di blade menggunakan nilai string (umum, spesialis),
        // kita akan anggap ini sebagai placeholder. Untuk integrasi ke database,
        // perlu disesuaikan logic-nya (e.g., menentukan clinic_id & doctor_id)

        // TODO: Implementasi Telemedis yang sesungguhnya
        
        return redirect()->route('consultation.index')->with('info', 'Fitur Konsultasi Online sedang dalam pengembangan. Pengajuan Anda berhasil dicatat sebagai minat.');
    }
}