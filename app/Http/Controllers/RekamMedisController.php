<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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