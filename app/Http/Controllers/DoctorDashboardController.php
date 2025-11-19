<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DoctorDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $today = Carbon::today()->toDateString();
        $startOfMonth = Carbon::now()->startOfMonth();

        // 1. Dapatkan data Doctor
        $doctor = Doctor::where('user_id', $user->id)->first();

        if (!$doctor) {
            // Jika data dokter tidak ditemukan, kirim data kosong
            $stats = $this->getEmptyStats();
            $todayAppointments = collect();
            $recentRecords = collect();
            $doctorName = $user->name ?? 'Dokter';

            return view('dokter.dashboard', compact('stats', 'todayAppointments', 'recentRecords', 'doctorName'));
        }

        $doctor_id = $doctor->id;
        $doctorName = $doctor->user->name ?? 'Dokter'; 
        
        // 2. Query Data Hari Ini (Appointments)
        $todayQuery = Appointment::where('doctor_id', $doctor_id)
            ->whereDate('tanggal_kunjungan', $today);

        // 3. Hitung Statistik
        $totalToday = $todayQuery->count();
        $completedToday = $todayQuery->where('status', 'selesai')->count();
        
        $rmMonth = MedicalRecord::where('doctor_id', $doctor_id)
            ->whereDate('tanggal_periksa', '>=', $startOfMonth)
            ->count();
            
        // Resep Tertunda (Kita hitung total resep yang dibuat bulan ini sebagai indikator)
        $pendingResep = Prescription::whereHas('medicalRecord', function($q) use ($doctor_id, $startOfMonth) {
            $q->where('doctor_id', $doctor_id)
              ->whereDate('tanggal_periksa', '>=', $startOfMonth);
        })->count();
        
        $stats = [
            'total_today' => $totalToday,
            'completed_today' => $completedToday,
            'rm_month' => $rmMonth,
            'pending_resep' => $pendingResep,
        ];

        // 4. Jadwal Kunjungan Hari Ini (Top 4)
        $todayAppointments = Appointment::where('doctor_id', $doctor_id)
            ->whereDate('tanggal_kunjungan', $today)
            ->whereIn('status', ['menunggu', 'disetujui', 'selesai'])
            ->with('patient.user')
            ->orderBy('jam_kunjungan', 'asc')
            ->limit(4)
            ->get();
            
        // 5. Rekam Medis Terakhir Diperbarui (Top 2)
        $recentRecords = MedicalRecord::where('doctor_id', $doctor_id)
            ->with('patient.user')
            ->orderBy('tanggal_periksa', 'desc')
            ->orderBy('updated_at', 'desc')
            ->limit(2)
            ->get();


        return view('dokter.dashboard', compact('stats', 'todayAppointments', 'recentRecords', 'doctorName'));
    }

    private function getEmptyStats()
    {
        return [
            'total_today' => 0,
            'completed_today' => 0,
            'rm_month' => 0,
            'pending_resep' => 0,
        ];
    }
}