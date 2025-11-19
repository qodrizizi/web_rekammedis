<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Medication;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DoctorPemeriksaanController extends Controller
{
    /**
     * Halaman Dashboard Dokter (Melihat Antrian Pasien)
     */
    public function index()
    {
        // Ambil user yang sedang login (Dokter)
        $user = Auth::user();
        
        // Ambil data dokter berdasarkan user_id
        $doctor = $user->doctor; 

        if (!$doctor) {
            return abort(403, 'Akun Anda tidak terhubung dengan data Dokter.');
        }

        // Ambil antrian pasien hari ini yang statusnya 'menunggu'
        // Khusus untuk dokter yang sedang login
        $antrian = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'menunggu')
            ->whereDate('tanggal_kunjungan', now())
            ->with('patient.user')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('dokter.dashboard', compact('antrian'));
    }

    /**
     * Halaman Form Pemeriksaan (Diagnosa & Resep)
     */
    public function periksa($id)
    {
        $appointment = Appointment::with(['patient.user', 'patient.medicalRecords'])->findOrFail($id);
        
        // Ambil daftar obat untuk dropdown resep (stok > 0)
        $medications = Medication::where('stok', '>', 0)->orderBy('nama_obat')->get();

        return view('dokter.periksa', compact('appointment', 'medications'));
    }

    /**
     * Simpan Hasil Pemeriksaan & Resep
     */
    public function store(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        
        $request->validate([
            'keluhan' => 'required|string',
            'diagnosa' => 'required|string',
            'tindakan' => 'nullable|string',
            // Validasi Array Obat (Resep)
            'resep' => 'nullable|array',
            'resep.*.medication_id' => 'required|exists:medications,id',
            'resep.*.jumlah' => 'required|integer|min:1',
            'resep.*.aturan_pakai' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            // 1. Simpan Rekam Medis
            $record = MedicalRecord::create([
                'patient_id' => $appointment->patient_id,
                'doctor_id' => $appointment->doctor_id,
                'clinic_id' => $appointment->clinic_id,
                'appointment_id' => $appointment->id,
                'tanggal_periksa' => now(),
                'keluhan' => $request->keluhan,
                'diagnosa' => $request->diagnosa,
                'tindakan' => $request->tindakan,
                'catatan_dokter' => $request->catatan,
                // PENTING: Set status obat jadi 'menunggu' agar muncul di Petugas Farmasi
                'status_pengambilan_obat' => $request->has('resep') ? 'menunggu' : 'tidak_ada_obat',
            ]);

            // 2. Simpan Resep Obat (Looping)
            if ($request->has('resep')) {
                foreach ($request->resep as $item) {
                    Prescription::create([
                        'medical_record_id' => $record->id,
                        'medication_id' => $item['medication_id'],
                        'jumlah' => $item['jumlah'],
                        'aturan_pakai' => $item['aturan_pakai'],
                    ]);
                }
            }

            // 3. Update Status Antrian jadi 'selesai' (agar hilang dari dashboard dokter)
            // Atau 'disetujui' jika Anda ingin membedakan status medis dan antrian
            $appointment->update(['status' => 'selesai']);

            DB::commit();
            return redirect()->route('dokter.dashboard')->with('success', 'Pemeriksaan selesai. Resep telah dikirim ke Farmasi.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan pemeriksaan: ' . $e->getMessage())->withInput();
        }
    }
}