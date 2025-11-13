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
        // 1. Inisialisasi variabel di luar scope IF
        $patient = null;
        $latestAppointment = null; 

        // 2. Ambil data Patient yang sedang login
        $patient = Patient::where('user_id', Auth::id())->first();

        // 3. Ambil ringkasan data penting untuk dashboard (Inisialisasi)
        $summary = [
            'active_appointments' => 0,
            'total_medical_records' => 0,
        ];

        if ($patient) {
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

        return view('pasien.dashboard', compact('summary', 'patient', 'latestAppointment'));
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
        
        // **PENAMBAHAN/PERUBAHAN:** Ambil ID pasien yang sudah ada untuk dikecualikan dari aturan unique
        $patient = Patient::where('user_id', $user->id)->first();
        $patientId = $patient ? $patient->id : 'NULL'; // Dapatkan ID pasien atau NULL jika belum ada

        // 1. Validasi Data
        $request->validate([
            // Ubah: unique:table,kolom,id_yang_diabaikan,kolom_id
            'nik' => 'required|string|max:20|unique:patients,nik,' . $patientId . ',id',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required|in:L,P',
            'no_bpjs' => 'nullable|string|max:30',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'nullable|string|max:500',
            'gol_darah' => 'nullable|string|max:3',
            // Tambahkan validasi untuk kontak darurat/alergi jika Anda sudah punya tabelnya.
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
        ];

        // 3. Simpan atau Update Data (Menggunakan updateOrCreate)
        Patient::updateOrCreate(
            ['user_id' => $user->id], // Kunci pencarian
            $patientData              // Data yang akan disimpan/diperbarui
        );

        return redirect()->route('pasien.profil')->with('success', 'Data Profil berhasil diperbarui!');
    }
}