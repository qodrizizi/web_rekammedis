<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Patient; // Tambahkan import Patient
use Illuminate\Support\Facades\Auth;

class ConsultationController extends Controller
{
    /**
     * Menampilkan halaman form pendaftaran konsultasi
     */
    public function index()
    {
        $user = Auth::user();
        $clinics = Clinic::select('id', 'nama_poli')->get();

        // 1. Cari ID Pasien yang sedang login
        $patientId = Patient::where('user_id', $user->id)->value('id'); 

        $activeAppointment = null;
        if ($patientId) {
            // 2. Cek Janji Temu Aktif: Status menunggu/disetujui DAN BELUM punya Medical Record
            $activeAppointment = Appointment::where('patient_id', $patientId)
                                            ->whereIn('status', ['menunggu', 'disetujui']) 
                                            // <<< INI KUNCINYA: Jika sudah ada MedicalRecord, dianggap sudah selesai
                                            ->doesntHave('medicalRecord') 
                                            ->with(['doctor.user', 'clinic'])
                                            ->latest()
                                            ->first();
        }

        return view('pasien.konsultasi', compact('clinics', 'activeAppointment'));
    }

    /**
     * API: Ambil Dokter berdasarkan ID Poli
     */
    public function getDoctorsByClinic($clinicId)
    {
        $doctors = Doctor::where('clinic_id', $clinicId)
                         ->with('user:id,name') 
                         ->get(['id', 'user_id', 'spesialis']);
        
        return response()->json($doctors);
    }

    /**
     * API: Ambil Jadwal berdasarkan ID Dokter
     */
    public function getDoctorSchedule($doctorId)
    {
        $doctor = Doctor::select('id', 'jadwal_praktek')->find($doctorId);
        
        // Slot waktu generik (bisa disesuaikan logic-nya untuk ketersediaan real-time)
        return response()->json([
            'jadwal_teks' => $doctor->jadwal_praktek ?? 'Jadwal belum diatur',
            'slots' => [
                '09:00', '09:30', '10:00', '10:30', '11:00', '13:00', '13:30', '14:00'
            ]
        ]);
    }

    /**
     * Proses Simpan Janji Temu (Tatap Muka)
     */
    public function store(Request $request)
    {
        $request->validate([
            'clinic_id' => 'required|exists:clinics,id',
            'doctor_id' => 'required|exists:doctors,id',
            'tanggal_kunjungan' => 'required|date|after_or_equal:today',
            'jam_kunjungan' => 'required',
            'keluhan' => 'nullable|string|max:500',
        ]);
        
        $user = Auth::user();
        $patientId = Patient::where('user_id', $user->id)->value('id');

        if (!$patientId) {
            return back()->with('error', 'Data pasien tidak ditemukan. Harap lengkapi profil terlebih dahulu.');
        }

        // Pengecekan Duplikasi Janji Temu Aktif (menggunakan logika doesn'tHave('medicalRecord'))
        $existing = Appointment::where('patient_id', $patientId)
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->doesntHave('medicalRecord')
            ->exists();

        if ($existing) {
            return back()->with('error', 'Anda masih memiliki janji temu yang aktif. Selesaikan terlebih dahulu.');
        }

        Appointment::create([
            'patient_id' => $patientId,
            'doctor_id' => $request->doctor_id,
            'clinic_id' => $request->clinic_id,
            'tanggal_kunjungan' => $request->tanggal_kunjungan,
            'jam_kunjungan' => $request->jam_kunjungan,
            'keluhan' => $request->keluhan,
            'status' => 'menunggu',
        ]);

        return redirect()->route('pasien.konsultasi')->with('success', 'Janji temu berhasil diajukan! Mohon datang tepat waktu.');
    }
}