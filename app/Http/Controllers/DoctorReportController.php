<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DoctorReportController extends Controller
{
    /**
     * Menampilkan laporan kinerja klinis untuk Dokter yang sedang login.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        // Pastikan mengambil data dokter berdasarkan user yang login
        $doctor = Doctor::where('user_id', $user->id)->first();
        
        // --- DEFINISI DEFAULT ---
        $monthlyTrend = []; 
        $stats = $this->getEmptyStats();
        // ------------------------

        // Tentukan rentang waktu default (Bulan Ini)
        $startOfMonth = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        
        // Jika ingin filter custom, bisa ditambahkan logika $request->has('start_date') disini
        $startDate = $startOfMonth; 
        
        // 1. Handle jika akun User login tapi data Dokter belum ada
        if (!$doctor) {
            return view('dokter.laporan', compact('stats', 'monthlyTrend')); 
        }

        $doctor_id = $doctor->id;
        
        // 2. Query Dasar untuk Statistik Periode Ini
        // Kita clone query agar bisa dipakai berulang tanpa menumpuk filter
        $baseQuery = MedicalRecord::where('doctor_id', $doctor_id)
            ->whereBetween('tanggal_periksa', [$startDate, $endDate]);

        // 3. Hitung Statistik
        $totalChecked = (clone $baseQuery)->count();
        
        // LOGIKA BARU: Pasien Unik (Lebih relevan buat dokter daripada 'RM Baru')
        // Menghitung berapa banyak pasien berbeda yang ditangani bulan ini
        $uniquePatients = (clone $baseQuery)->distinct('patient_id')->count('patient_id');

        // PERBAIKAN ERROR SQL DISINI
        // Menggunakan orderByRaw('count(*) desc') alih-alih alias 'total'
        $topDiagnoses = (clone $baseQuery)
            ->select('diagnosa', DB::raw('count(*) as total'))
            ->whereNotNull('diagnosa') // Pastikan diagnosa tidak kosong
            ->where('diagnosa', '!=', '')
            ->groupBy('diagnosa')
            ->orderByRaw('count(*) desc') // FIX: Mengurutkan berdasarkan hasil hitungan langsung
            ->limit(5)
            ->get();
            
        // Hitung Diagnosis Unik (Variasi penyakit yang ditangani)
        $uniqueDiagnoses = (clone $baseQuery)->distinct('diagnosa')->count('diagnosa');
        
        // Placeholder Rata-rata Waktu (Tetap dummy karena tidak ada kolom jam_selesai di DB)
        // Logika: Jika ada pasien, anggap rata-rata 15 menit.
        $avgTime = $totalChecked > 0 ? 15 : 0; 

        $stats = [
            'total_checked' => $totalChecked,
            'new_records' => $uniquePatients, // Kita ganti isinya jadi pasien unik
            'unique_diagnoses' => $uniqueDiagnoses,
            'avg_time' => $avgTime,
            'top_diagnoses' => $topDiagnoses,
        ];
        
        // 4. Hitung Data Tren (Ambil data 12 bulan terakhir)
        $startOfLastYear = Carbon::now()->subMonths(11)->startOfMonth();
        $allRecordsForTrend = MedicalRecord::where('doctor_id', $doctor_id)
            ->whereBetween('tanggal_periksa', [$startOfLastYear, Carbon::now()->endOfMonth()])
            ->get();

        $monthlyTrend = $this->getMonthlyTrendData($allRecordsForTrend, $startOfLastYear);

        return view('dokter.laporan', compact('stats', 'monthlyTrend'));
    }
    
    private function getEmptyStats()
    {
        return [
            'total_checked' => 0,
            'new_records' => 0,
            'unique_diagnoses' => 0,
            'avg_time' => 0,
            'top_diagnoses' => collect(),
        ];
    }
    
    private function getMonthlyTrendData(Collection $records, $startDate)
    {
        $endDate = Carbon::now();
        $monthlyData = [];

        // Inisialisasi array bulan agar grafik tidak bolong jika bulan tsb kosong datanya
        $period = \Carbon\CarbonPeriod::create($startDate, '1 month', $endDate);

        foreach ($period as $date) {
            // Format key: "Nov 2025" agar unik per tahun
            $monthKey = $date->translatedFormat('M Y'); 
            $monthlyData[$monthKey] = 0;
        }

        // Isi data real
        $records->each(function ($record) use (&$monthlyData) {
            $monthKey = Carbon::parse($record->tanggal_periksa)->translatedFormat('M Y');
            // Pastikan key ada (menghindari error jika tanggal di luar range inisialisasi)
            if (isset($monthlyData[$monthKey])) {
                $monthlyData[$monthKey]++;
            }
        });

        $trendArray = [];
        foreach ($monthlyData as $month => $count) {
            $trendArray[] = ['month' => $month, 'count' => $count];
        }
        
        // Ambil 6 bulan terakhir saja untuk view
        return array_slice($trendArray, -6, 6);
    }
    
    public function exportPdf(Request $request)
    {
        return back()->with('success', 'Fitur export PDF akan segera hadir.');
    }
}