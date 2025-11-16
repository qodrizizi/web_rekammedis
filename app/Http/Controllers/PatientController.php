<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use App\Models\MedicalRecord;
use App\Models\Appointment;

class PatientController extends Controller
{
    /**
     * Menampilkan Dashboard Pasien.
     */
    public function dashboard()
    {
        $user = Auth::user(); // Tambahkan user di sini
        $patient = null;
        $latestAppointment = null; 
        
        // 1. Ambil data Patient, termasuk field riwayat_alergi
        $patient = Patient::where('user_id', Auth::id())->first();

        // 2. Tentukan status alergi di luar if
        $alergiTercatat = false; // Default: Tidak ada alergi
        
        // 3. Ambil ringkasan data penting untuk dashboard (Inisialisasi)
        $summary = [
            'active_appointments' => 0,
            'total_medical_records' => 0,
        ];

        if ($patient) {
            // Cek apakah field riwayat_alergi memiliki isi (string non-empty)
            if (!empty($patient->riwayat_alergi)) {
                $alergiTercatat = true;
            }

            $summary['active_appointments'] = Appointment::where('patient_id', $patient->id)
                                                     ->whereIn('status', ['menunggu', 'disetujui'])
                                                     ->count();
            
            $summary['total_medical_records'] = MedicalRecord::where('patient_id', $patient->id)->count();
            
            $latestAppointment = Appointment::where('patient_id', $patient->id)
                                                ->where('tanggal_kunjungan', '>=', now()->toDateString())
                                                ->whereIn('status', ['menunggu', 'disetujui'])
                                                ->orderBy('tanggal_kunjungan')
                                                ->orderBy('jam_kunjungan')
                                                ->with('doctor.user', 'clinic')
                                                ->first();
        }

        // 4. Kirim variabel baru ($alergiTercatat) ke view
        return view('pasien.dashboard', compact('summary', 'patient', 'latestAppointment', 'user', 'alergiTercatat'));
    }

    //-----------------------------------------------------------------------------------

    /**
     * Menampilkan halaman Rekam Medis Pasien.
     */
    public function medicalRecord()
    {
        // GANTI firstOrFail() menjadi first()
        $patient = Patient::where('user_id', Auth::id())->first();

        if (!$patient) {
            // Jika pasien belum mengisi data dasar, arahkan ke halaman profil
            return redirect()->route('pasien.profil')->with('error', 'Anda harus melengkapi data profil (NIK, Tanggal Lahir) terlebih dahulu untuk melihat Rekam Medis.');
        }

        $medicalRecords = MedicalRecord::where('patient_id', $patient->id)
                                    ->with('doctor.user', 'clinic') 
                                    ->orderBy('tanggal_periksa', 'desc')
                                    ->paginate(10); 

        return view('pasien.rekam_medis', compact('medicalRecords', 'patient'));
    }

    //-----------------------------------------------------------------------------------

    /**
     * Menampilkan halaman Profil Pasien (Form Create/Update).
     */
    public function profile()
    {
        $user = Auth::user();

        // GANTI firstOrFail() menjadi first() dan inisialisasi objek baru
        $patient = Patient::where('user_id', $user->id)->first();
        
        // Inisialisasi Patient baru jika data belum ada
        if (!$patient) {
            $patient = new Patient(['user_id' => $user->id]);
        }

        return view('pasien.profil', compact('user', 'patient'));
    }

    //-----------------------------------------------------------------------------------

    /**
     * Menyimpan atau Memperbarui data Profil Pasien (dari form).
     */
    public function storeProfile(Request $request)
    {
        $user = Auth::user();
        
        $patientId = Patient::where('user_id', $user->id)->value('id');

        // 1. Validasi Data
        $request->validate([
            'nik' => 'required|string|max:20|unique:patients,nik,' . $patientId . ',id',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'no_bpjs' => 'nullable|string|max:30',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'nullable|string|max:500',
            'gol_darah' => 'nullable|string|max:3|in:A,B,AB,O,N/A',
            
            // TAMBAHAN: Validasi untuk field Riwayat Teks
            'catatan_kronis' => 'nullable|string|max:1000',
            'alergi_obat' => 'nullable|string|max:1000',
        ]);
        
        // 2. Siapkan data untuk disimpan/diperbarui
        $patientData = [
            'user_id' => $user->id,
            'nik' => $request->nik,
            'no_bpjs' => $request->no_bpjs,
            'alamat' => $request->alamat,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'gol_darah' => $request->gol_darah,
            'no_hp' => $request->no_hp,
            
            // TAMBAHAN: Masukkan field baru ke data
            'riwayat_kronis' => $request->catatan_kronis, 
            'riwayat_alergi' => $request->alergi_obat,
        ];

        // 3. Simpan atau Update Data (Menggunakan updateOrCreate)
        Patient::updateOrCreate(
            ['user_id' => $user->id],
            $patientData
        );

        return redirect()->route('pasien.profil')->with('success', 'Data Profil dan Riwayat Kesehatan Dasar berhasil diperbarui!');
    }
}