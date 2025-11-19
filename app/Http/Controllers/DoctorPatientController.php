<?php
// app/Http/Controllers/DoctorPatientController.php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Carbon\Carbon;

// Tambahkan import untuk Paginator
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class DoctorPatientController extends Controller
{
    public function index(Request $request) // Tambahkan $request
    {
        $user = auth()->user();
        $doctor = Doctor::where('user_id', $user->id)->first();

        if (!$doctor) {
            $antrianHariIni = collect();
            
            // Perbaikan untuk BadMethodCallException: Buat Paginator Kosong
            $patients = new LengthAwarePaginator(
                Collection::make([]), // Data kosong
                0,                     // Total items = 0
                10,                    // Items per page
                1,                     // Current page
                ['path' => $request->url()] // Path untuk link pagination
            );
            
            return view('dokter.pasien', compact('patients', 'antrianHariIni'));
        }

        $today = Carbon::today()->toDateString();

        // 1. Antrian Hari Ini (Appointments)
        $antrianHariIni = Appointment::where('doctor_id', $doctor->id)
                                     ->whereDate('tanggal_kunjungan', $today)
                                     ->whereIn('status', ['menunggu', 'disetujui']) 
                                     ->with(['patient.user'])
                                     ->orderBy('jam_kunjungan', 'asc')
                                     ->get();
        
        // 2. Semua Pasien Saya
        $patientIds = $doctor->medicalRecords()->pluck('patient_id')->unique();
        
        $patients = Patient::whereIn('id', $patientIds)
                            ->with(['user', 'medicalRecords' => function ($query) {
                                $query->orderBy('tanggal_periksa', 'desc')->limit(1);
                            }])
                            ->orderBy('created_at', 'desc')
                            ->paginate(10); 
        
        return view('dokter.pasien', compact('patients', 'antrianHariIni'));
    }
    public function getPatientHistory($id)
    {
        $patient = Patient::with(['user', 'medicalRecords' => function($q) {
            // Ambil riwayat urut dari yang terbaru
            $q->orderBy('tanggal_periksa', 'desc')
              ->with(['doctor.user', 'prescriptions.medication']); // Load relasi dokter & resep
        }])->findOrFail($id);

        // Format data untuk dikirim ke Javascript
        return response()->json([
            'id' => $patient->id,
            'nama' => $patient->user->name,
            'nik' => $patient->nik,
            'jenis_kelamin' => $patient->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan',
            'usia' => \Carbon\Carbon::parse($patient->tanggal_lahir)->age . ' Tahun',
            'gol_darah' => $patient->gol_darah ?? '-',
            'riwayat' => $patient->medicalRecords->map(function($record) {
                return [
                    'tanggal' => \Carbon\Carbon::parse($record->tanggal_periksa)->format('d M Y'),
                    'dokter' => $record->doctor->user->name ?? 'Dokter Umum',
                    'keluhan' => $record->keluhan,
                    'diagnosa' => $record->diagnosa,
                    'tindakan' => $record->tindakan,
                    'catatan' => $record->catatan_dokter,
                    'resep' => $record->prescriptions->map(function($resep) {
                        return $resep->medication->nama_obat . ' (' . $resep->jumlah . ' ' . $resep->medication->satuan . ') - ' . $resep->aturan_pakai;
                    }),
                ];
            })
        ]);
    }
}