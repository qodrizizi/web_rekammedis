<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Medication;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil Data Statistik Cepat (Stats Cards)
        
        // Total Pasien Terdaftar (Role ID 4 = Pasien)
        $totalPatients = Patient::count(); 

        // Janji Kunjungan Hari Ini (Status: Menunggu atau Disetujui)
        $today = Carbon::today();
        $todayAppointments = Appointment::whereDate('tanggal_kunjungan', $today)
                                        ->whereIn('status', ['menunggu', 'disetujui'])
                                        ->count();

        // Total Dokter Aktif (Role ID 2 = Dokter)
        $activeDoctors = Doctor::count();

        // Stok Obat Kritis (Asumsi kritis < 50 unit)
        $criticalStockThreshold = 50;
        $criticalMedicationsCount = Medication::where('stok', '<', $criticalStockThreshold)->count();

        $stats = [
            'total_pasien' => number_format($totalPatients),
            'janji_hari_ini' => $todayAppointments,
            'dokter_aktif' => $activeDoctors,
            'stok_kritis' => $criticalMedicationsCount,
        ];

        // 2. Ambil Daftar Janji Kunjungan Mendatang (Appointments List)
        // Ambil 5 janji yang paling dekat, status menunggu/disetujui
        $upcomingAppointments = Appointment::with(['patient.user', 'doctor.user', 'clinic'])
                                            ->whereIn('status', ['menunggu', 'disetujui'])
                                            ->whereDate('tanggal_kunjungan', '>=', $today)
                                            ->orderBy('tanggal_kunjungan', 'asc')
                                            ->orderBy('jam_kunjungan', 'asc')
                                            ->take(5)
                                            ->get();

        // 3. Ambil Log Aktivitas Terbaru (Activity Logs)
        // Note: Asumsi ActivityLog sudah diisi oleh sistem Anda.
        $recentActivityLogs = ActivityLog::with('user')
                                        ->latest('waktu')
                                        ->take(5)
                                        ->get();
        
        // Data untuk Grafik (Contoh: Kunjungan 6 Bulan Terakhir)
        // Ini akan membutuhkan query complex (agregasi bulanan), kita sediakan array kosong dulu.
        $monthlyVisits = $this->getMonthlyVisitData();


        return view('admin.dashboard', compact(
            'stats',
            'upcomingAppointments',
            'recentActivityLogs',
            'monthlyVisits'
        ));
    }

    /**
     * Helper function untuk mengambil data kunjungan bulanan untuk grafik.
     */
    protected function getMonthlyVisitData()
    {
        $months = 6;
        $start = Carbon::now()->subMonths($months - 1)->startOfMonth();
        $end = Carbon::now()->endOfMonth();
        
        $results = Appointment::select(
                DB::raw('YEAR(tanggal_kunjungan) as year'),
                DB::raw('MONTH(tanggal_kunjungan) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('status', 'selesai')
            ->whereBetween('tanggal_kunjungan', [$start, $end])
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $data = [];
        $labels = [];
        
        // Inisialisasi data untuk 6 bulan terakhir
        for ($i = 0; $i < $months; $i++) {
            $date = Carbon::now()->subMonths($months - 1 - $i);
            $labels[] = $date->isoFormat('MMM YYYY');
            $data[] = 0;
        }

        // Isi data berdasarkan hasil query
        foreach ($results as $result) {
            $monthYear = Carbon::create($result->year, $result->month)->isoFormat('MMM YYYY');
            $index = array_search($monthYear, $labels);
            if ($index !== false) {
                $data[$index] = $result->count;
            }
        }
        
        return ['labels' => $labels, 'data' => $data];
    }
}