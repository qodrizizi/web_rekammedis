<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class PendaftaranController extends Controller
{
    /**
     * READ: Menampilkan daftar semua Janji Temu (Index).
     */
    public function index(Request $request)
    {
        // Mendapatkan data pendukung untuk dropdown
        $patients = Patient::with('user')->get()->map(function ($p) {
            return ['id' => $p->id, 'name' => $p->user->name ?? 'Pasien Tidak Dikenal'];
        });
        $doctors = Doctor::with('user')->get()->map(function ($d) {
            return ['id' => $d->id, 'name' => $d->user->name ?? 'Dokter Tidak Dikenal', 'spesialis' => $d->spesialis];
        });
        $clinics = Clinic::all();
        $statuses = ['menunggu', 'disetujui', 'selesai', 'batal'];

        $query = Appointment::with(['patient.user', 'doctor.user', 'clinic']);

        // Filter status (jika ada)
        if ($request->filled('status') && in_array($request->status, $statuses)) {
            $query->where('status', $request->status);
        } else {
             // Default: Hanya tampilkan status yang belum selesai (menunggu, disetujui, batal)
             $query->whereIn('status', ['menunggu', 'disetujui', 'batal']);
        }

        // Filter tanggal (default hari ini jika tidak ada filter lain)
        $dateFilter = $request->input('date', Carbon::now()->format('Y-m-d'));
        if ($dateFilter) {
            $query->whereDate('tanggal_kunjungan', $dateFilter);
        }

        $appointments = $query->latest('tanggal_kunjungan')->latest('jam_kunjungan')->paginate(10); 

        return view('admin.pendaftaran', compact('appointments', 'patients', 'doctors', 'clinics', 'statuses'));
    }

    /**
     * CREATE: Menyimpan Janji Temu baru (Store).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'clinic_id' => 'required|exists:clinics,id',
            'tanggal_kunjungan' => 'required|date|after_or_equal:today', // Pastikan tgl di masa depan
            'jam_kunjungan' => 'nullable|date_format:H:i',
            'keluhan' => 'required|string',
            // Status default: 'menunggu'
        ]);

        $validated['status'] = 'menunggu';
        Appointment::create($validated);
        
        return redirect()->route('admin.pendaftaran')->with('success', 'Janji Temu berhasil dibuat. Status: Menunggu.');
    }

    /**
     * UPDATE: Memperbarui Janji Temu (Update).
     */
    public function update(Request $request, Appointment $pendaftaran)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'clinic_id' => 'required|exists:clinics,id',
            'tanggal_kunjungan' => 'required|date',
            'jam_kunjungan' => 'nullable|date_format:H:i',
            'keluhan' => 'required|string',
            'status' => ['required', Rule::in(['menunggu', 'disetujui', 'selesai', 'batal'])],
        ]);
        
        $pendaftaran->update($validated);

        return redirect()->route('admin.pendaftaran')->with('success', 'Janji Temu berhasil diperbarui. Status: ' . $validated['status']);
    }

    /**
     * Tindakan Cepat: Mengubah status Janji Temu (Misal: Disetujui, Selesai, Batal).
     */
    public function updateStatus(Request $request, Appointment $pendaftaran)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['menunggu', 'disetujui', 'selesai', 'batal'])],
        ]);

        $pendaftaran->update(['status' => $validated['status']]);

        return redirect()->route('admin.pendaftaran')->with('success', 'Status Janji Temu #' . $pendaftaran->id . ' diubah menjadi ' . $validated['status']);
    }
    
    /**
     * DELETE: Menghapus Janji Temu (Destroy).
     */
    public function destroy(Appointment $pendaftaran)
    {
        $pendaftaran->delete();

        return redirect()->route('admin.pendaftaran')->with('success', 'Janji Temu #' . $pendaftaran->id . ' berhasil dibatalkan dan dihapus.');
    }
}