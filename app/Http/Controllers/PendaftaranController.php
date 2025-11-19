<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Clinic; // Tambahkan
use App\Models\Doctor; // Tambahkan

class PendaftaranController extends Controller
{
    /**
     * Menampilkan formulir pendaftaran (index)
     */
    public function index()
    {
        // Ambil semua data Poliklinik untuk di-load pertama kali
        $clinics = Clinic::all(['id', 'nama_poli']);
        
        // Ambil semua data Dokter dengan nama user-nya
        $doctors = Doctor::with('user')->get()->map(function ($doctor) {
            return [
                'user_id' => $doctor->user_id,
                'name' => $doctor->user->name,
                'clinic_id' => $doctor->clinic_id, // Asumsi ada field clinic_id di tabel doctors
                'spesialis' => $doctor->spesialis,
            ];
        });

        // Akan lebih baik jika data Poli dan Dokter di-load melalui AJAX/API, 
        // tapi untuk langkah awal, kita kirim data Poli saja.
        return view('petugas.pendaftaran', compact('clinics'));
    }

    // =========================================================================
    //                            AJAX ENDPOINTS
    // =========================================================================

    /**
     * Endpoint API untuk mengambil semua Poliklinik (dipanggil oleh Alpine/JS)
     */
    public function getClinics()
    {
        $clinics = Clinic::all(['id', 'nama_poli']);
        return response()->json($clinics);
    }

    /**
     * Endpoint API untuk mengambil Dokter berdasarkan ID Poliklinik
     * @param int $clinic_id
     */
    public function getDoctorsByClinic($clinic_id)
    {
        // ASUMSI: Tabel doctors memiliki Foreign Key clinic_id.
        // Jika tidak ada, Anda mungkin perlu logic join antara spesialis dan nama poli.
        $doctors = Doctor::where('clinic_id', $clinic_id)
                         ->with('user')
                         ->get();
                         
        $result = $doctors->map(function ($doctor) {
            return [
                'id' => $doctor->id, // <-- UBAH INI
                'name' => $doctor->user->name . ' (' . $doctor->spesialis . ')',
            ];
        });

        return response()->json($result);
    }

    /**
     * Endpoint API untuk mengambil Jadwal Dokter berdasarkan User ID Dokter
     * @param int $user_id
     */
    public function getDoctorSchedule($user_id)
    {
        $doctor = Doctor::where('user_id', $user_id)->first();

        if (!$doctor) {
            return response()->json(['error' => 'Dokter tidak ditemukan'], 404);
        }

        // ASUMSI: kolom jadwal_praktek adalah JSON array of strings: ["Senin (08:00-12:00)", "Rabu (13:00-17:00)"]
        // Jika kolomnya string biasa, Anda perlu parse di sini.
        $schedule = json_decode($doctor->jadwal_praktek, true) ?? [];

        // Untuk view, kita hanya ingin tanggal-tanggal yang valid untuk seminggu ke depan
        $availableDates = [];
        $today = now();

        for ($i = 0; $i < 7; $i++) {
            $date = $today->copy()->addDays($i);
            $dayName = $date->dayName; // e.g., Monday

            foreach ($schedule as $slot) {
                // Sederhana: cek apakah nama hari ada di slot jadwal
                if (str_contains($slot, $dayName)) {
                    // Hanya tampilkan tanggal, waktu akan di-handle di frontend jika perlu
                    $availableDates[] = [
                        'date' => $date->toDateString(),
                        'day' => $dayName,
                        'slot' => $slot
                    ];
                }
            }
        }

        return response()->json([
            'spesialis' => $doctor->spesialis,
            'raw_schedule' => $doctor->jadwal_praktek,
            'available_dates' => $availableDates
        ]);
    }
    
    public function searchPatient(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return response()->json(['error' => 'Query pencarian kosong'], 400);
        }

        // =================================================================
        // PERBAIKAN: Tambahkan ->with('user')
        // Ini akan "eager load" relasi 'user' sehingga $patient->user
        // tidak akan kosong.
        // =================================================================
        $patient = Patient::with('user') 
                            ->where('nik', $query)
                            ->orWhereHas('user', function($q) use ($query) {
                                $q->where('name', 'LIKE', "%{$query}%");
                            })
                            ->first();

        // Cek apakah pasien DAN relasi user-nya ditemukan
        if ($patient && $patient->user) {
            
            // Hitung umur
            $age = $patient->tanggal_lahir ? \Carbon\Carbon::parse($patient->tanggal_lahir)->age : null;

            // Tentukan pembayaran terakhir atau default
            $lastAppointment = $patient->appointments()->latest('tanggal_kunjungan')->first();
            $paymentType = 'Umum'; // Default
            if ($lastAppointment && str_contains($lastAppointment->keluhan, 'BPJS')) {
                $paymentType = 'BPJS Kesehatan';
            } elseif($patient->no_bpjs) {
                 $paymentType = 'BPJS Kesehatan';
            }

            $result = [
                'id' => $patient->id,
                'name' => $patient->user->name, // <-- SEKARANG PASTI BERISI NAMA
                'age' => $age,
                'nik' => $patient->nik,
                'payment' => $paymentType 
            ];

            return response()->json($result);
        }

        return response()->json(['error' => 'Pasien tidak ditemukan'], 404);
    }

    // =========================================================================
    //                            LOGIC PENYIMPANAN
    // =========================================================================

    /**
     * Menyimpan Pasien Baru dan mendaftarkan Kunjungan Pertamanya.
     */
    public function storeNewPatientRegistration(Request $request)
    {
        // 1. Validasi Data (Semua Wajib Diisi)
        // 1. Validasi Data (Semua Wajib Diisi)
        $validatedData = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|max:16|unique:patients,nik',
            'email' => 'required|string|email|max:255|unique:users,email', // <-- TAMBAHAN BARU
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string',
            'no_bpjs' => 'nullable|string|max:30',
            'pembayaran' => 'required|in:umum,bpjs,asuransi',
            
            'poli_tujuan' => 'required|exists:clinics,id', 
            'dokter_tujuan' => 'required|exists:doctors,id',
            'tanggal_kunjungan' => 'required|date_format:Y-m-d', // <-- Pastikan ini ada
        ]);

        try {
            DB::beginTransaction(); 

            // A. Buat Akun User Baru (Role Pasien = 4)
            $user = User::create([
                'name' => $validatedData['nama_lengkap'],
                'email' => $validatedData['email'], // <-- DIUBAH
                'password' => Hash::make($validatedData['nik']), // Menggunakan NIK sebagai default password
                'role_id' => 4,
            ]);

            // B. Buat Data Pasien (Tabel patients)
            $patient = Patient::create([
                'user_id' => $user->id,
                'nik' => $validatedData['nik'],
                'no_bpjs' => $validatedData['no_bpjs'],
                'alamat' => $validatedData['alamat'],
                'tanggal_lahir' => $validatedData['tanggal_lahir'],
                'jenis_kelamin' => $validatedData['jenis_kelamin'],
                'gol_darah' => $request->input('gol_darah') ?? null, 
                'no_hp' => $request->input('no_hp') ?? null,     
            ]);

            // C. Daftarkan Kunjungan (Tabel appointments)
            $jenisPembayaran = $validatedData['pembayaran']; 

            Appointment::create([
                'patient_id' => $patient->id,
                'doctor_id' => $validatedData['dokter_tujuan'],
                'clinic_id' => $validatedData['poli_tujuan'],
                'tanggal_kunjungan' => $validatedData['tanggal_kunjungan'], 
                'status' => 'menunggu',
                'keluhan' => 'Pendaftaran pasien baru dan kunjungan pertama. Jenis Pembayaran: ' . strtoupper($jenisPembayaran),
            ]);

            DB::commit();

            return redirect()->route('petugas.pendaftaran')->with('success', 'Pasien baru dan pendaftaran kunjungan berhasil disimpan! ID Pasien: ' . $patient->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menyimpan Pendaftaran Kunjungan untuk Pasien Lama.
     */
    public function storeOldPatientRegistration(Request $request)
    {
        $validatedData = $request->validate([
            'patient_id' => 'required|exists:patients,id', 
            'poli_tujuan_lama' => 'required|exists:clinics,id',
            'dokter_tujuan_lama' => 'required|exists:doctors,id',
            'tanggal_kunjungan_lama' => 'required|date_format:Y-m-d', // WAJIB PILIH TANGGAL
            'keluhan_lama' => 'required|string', // WAJIB ISI KELUHAN
        ]);

        try {
            $patient = Patient::find($validatedData['patient_id']);
            $pembayaran = $request->input('pembayaran_lama', 'Umum'); // Ambil dari form jika ada, default Umum
            
            Appointment::create([
                'patient_id' => $validatedData['patient_id'],
                'doctor_id' => $validatedData['dokter_tujuan_lama'],
                'clinic_id' => $validatedData['poli_tujuan_lama'],
                'tanggal_kunjungan' => $validatedData['tanggal_kunjungan_lama'],
                'status' => 'menunggu',
                'keluhan' => "Keluhan: " . $validatedData['keluhan_lama'] . ". Jenis Pembayaran: " . strtoupper($pembayaran),
            ]);

            return redirect()->route('petugas.pendaftaran')->with('success', 'Pendaftaran kunjungan pasien lama berhasil disimpan!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan pendaftaran: ' . $e->getMessage());
        }
    }
}