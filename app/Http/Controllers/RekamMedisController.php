<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
// Tambahkan import untuk Paginator
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class RekamMedisController extends Controller
{
    /**
     * READ: Menampilkan daftar semua Rekam Medis (Index).
     */
    public function index()
    {
        // Ambil data Rekam Medis dengan semua relasi yang diperlukan untuk tabel
        $records = MedicalRecord::with(['patient.user', 'doctor.user', 'clinic'])
                            ->latest('tanggal_periksa')
                            ->paginate(10); 
        
        // Data pendukung untuk dropdown di modal (Add/Edit)
        $patients = Patient::with('user')->get()->map(function ($p) {
            return ['id' => $p->id, 'name' => $p->user->name ?? 'Pasien Tidak Dikenal'];
        });
        $doctors = Doctor::with('user')->get()->map(function ($d) {
            return ['id' => $d->id, 'name' => $d->user->name ?? 'Dokter Tidak Dikenal', 'spesialis' => $d->spesialis];
        });
        $clinics = Clinic::all();

        return view('admin.rekam_medis', compact('records', 'patients', 'doctors', 'clinics'));
    }
    // FOKUS DI SINI: Menampilkan Riwayat Pasien Khusus Dokter Login
    public function indexDoctorRecord(Request $request)
    {
        $user = auth()->user();
        $doctor = Doctor::where('user_id', $user->id)->first();

        // Jika bukan dokter, return kosong
        if (!$doctor) {
            $medicalRecords = new LengthAwarePaginator([], 0, 15, 1, ['path' => $request->url()]);
            return view('dokter.rekam_medis', compact('medicalRecords'));
        }

        // Query Dasar
        $query = MedicalRecord::where('doctor_id', $doctor->id)
            ->with(['patient.user']); // Eager load user pasien

        // Logika Pencarian (Search)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // Cari berdasarkan Nama Pasien
                $q->whereHas('patient.user', function($subQ) use ($search) {
                    $subQ->where('name', 'like', '%' . $search . '%');
                })
                // Atau cari berdasarkan Diagnosa
                ->orWhere('diagnosa', 'like', '%' . $search . '%')
                // Atau cari berdasarkan ID Medical Record
                ->orWhere('id', $search);
            });
        }

        // Urutkan dan Paginate
        $medicalRecords = $query->orderBy('tanggal_periksa', 'desc')
                                ->paginate(10)
                                ->withQueryString(); // Agar search tidak hilang saat ganti halaman

        return view('dokter.rekam_medis', compact('medicalRecords'));
    }
    /**
     * CREATE: Menyimpan data Rekam Medis baru (Store).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'clinic_id' => 'required|exists:clinics,id',
            'tanggal_periksa' => 'required|date',
            'keluhan' => 'required|string',
            'diagnosa' => 'required|string',
            'tindakan' => 'nullable|string',
            'catatan_dokter' => 'nullable|string',
        ]);

        MedicalRecord::create($validated);
        
        return redirect()->route('admin.rekam_medis')->with('success', 'Rekam Medis baru berhasil ditambahkan.');
    }

    public function storeDoctor(Request $request)
    {
        // 1. Validasi Data
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'diagnosa' => 'required|string|max:255',
            'tindakan' => 'required|string',
            // Tambahkan validasi untuk vital signs jika diperlukan
            // 'vital_signs' => 'nullable|array', 
        ]);

        // 2. Ambil Doctor/Clinic ID
        $doctor = Doctor::find($request->doctor_id);

        // Asumsi: Anda perlu menentukan clinic_id.
        // Dalam sistem nyata, ini bisa diambil dari jadwal dokter hari itu.
        // Untuk saat ini, kita akan menggunakan nilai default atau mencari poliklinik Dokter.
        $clinicId = 1; // Ganti dengan logika yang sesuai, misal $doctor->clinic_id

        // 3. Simpan Rekam Medis Baru
        $rekamMedis = MedicalRecord::create([
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'clinic_id' => $clinicId, // Sesuaikan
            'tanggal_periksa' => Carbon::now(),
            'keluhan' => $request->input('keluhan_from_appointment', 'Pemeriksaan dadakan/Lanjutan'),
            'diagnosa' => $request->diagnosa,
            'tindakan' => $request->tindakan,
            'catatan_dokter' => $request->catatan_dokter,
            // Anda dapat menambahkan Vital Signs ke field keluhan atau field terpisah
        ]);

        // 4. Update Status Appointment (Jika ada dari Antrian)
        // Di sini Anda bisa mencari appointment hari ini dengan patient_id yang sama
        $appointment = Appointment::where('patient_id', $request->patient_id)
                                  ->where('doctor_id', $request->doctor_id)
                                  ->whereDate('tanggal_kunjungan', Carbon::today())
                                  ->whereIn('status', ['menunggu', 'disetujui'])
                                  ->first();

        if ($appointment) {
            $appointment->status = 'selesai';
            $appointment->save();
        }

        // 5. Redirect dengan pesan sukses
        return redirect()->route('dokter.pasien')->with('success', 'Hasil pemeriksaan pasien berhasil disimpan!');
    }

    /**
     * UPDATE: Memperbarui data Rekam Medis (Update).
     */
    public function update(Request $request, MedicalRecord $rekam_medi) // Menggunakan $rekam_medi
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'clinic_id' => 'required|exists:clinics,id',
            'tanggal_periksa' => 'required|date',
            'keluhan' => 'required|string',
            'diagnosa' => 'required|string',
            'tindakan' => 'nullable|string',
            'catatan_dokter' => 'nullable|string',
        ]);
        
        $rekam_medi->update($validated);

        return redirect()->route('admin.rekam_medis')->with('success', 'Rekam Medis berhasil diperbarui.');
    }

    /**
     * DELETE: Menghapus data Rekam Medis (Destroy).
     */
    public function destroy(MedicalRecord $rekam_medi)
    {
        $rekam_medi->delete();

        return redirect()->route('admin.rekam_medis')->with('success', 'Rekam Medis berhasil dihapus.');
    }

    
}