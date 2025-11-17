<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Clinic;
use App\Models\Prescription;
use App\Models\Medication;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    /**
     * Menampilkan Laporan dan memproses filter.
     */
    public function index(Request $request)
    {
        // 1. Ambil data pendukung untuk filter dropdown
        $doctors = \App\Models\Doctor::with('user')->get();
        $clinics = Clinic::all();
        $reportType = $request->input('report_type', 'kunjungan');
        
        // 2. Tentukan periode default/input
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::now()->endOfDay();
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : $endDate->copy()->subDays(30)->startOfDay();
        $filterSpecific = $request->input('filter_specific');
        $reportData = [];
        $summary = [];

        // 3. Proses Query berdasarkan Jenis Laporan
        switch ($reportType) {
            case 'diagnosa':
                [$reportData, $summary] = $this->getTopDiagnosaReport($startDate, $endDate);
                break;
            case 'obat':
                [$reportData, $summary] = $this->getObatUsageReport($startDate, $endDate);
                break;
            case 'kunjungan':
            default:
                [$reportData, $summary] = $this->getKunjunganReport($startDate, $endDate, $filterSpecific);
                $reportType = 'kunjungan';
                break;
        }

        // 4. Kirim data ke view
        return view('admin.laporan', compact(
            'doctors', 
            'clinics', 
            'startDate', 
            'endDate', 
            'reportType', 
            'filterSpecific', 
            'reportData', 
            'summary'
        ));
    }

    // --- LOGIC LAPORAN ---

    protected function getKunjunganReport($startDate, $endDate, $filterSpecific)
    {
        // A. Summary (Total Kunjungan, Pasien Baru, Rata-rata)
        $totalDays = $startDate->diffInDays($endDate) + 1;
        
        $summary['total_kunjungan'] = MedicalRecord::whereBetween('tanggal_periksa', [$startDate, $endDate])->count();

        // Query untuk menghitung pasien yang baru pertama kali periksa dalam periode ini
        $summary['pasien_baru'] = DB::table('patients')
            ->whereExists(function ($query) use ($startDate, $endDate) {
                $query->select(DB::raw(1))
                      ->from('medical_records')
                      ->whereRaw('medical_records.patient_id = patients.id')
                      ->whereBetween('tanggal_periksa', [$startDate, $endDate])
                      ->whereRaw('tanggal_periksa = (SELECT MIN(tanggal_periksa) FROM medical_records WHERE patient_id = patients.id)');
            })
            ->count();
        
        $summary['rata_rata_harian'] = $summary['total_kunjungan'] > 0 ? number_format($summary['total_kunjungan'] / $totalDays, 2) : 0;

        // B. Detail Per Hari/Poli
        $clinics = Clinic::pluck('nama_poli', 'id')->toArray();
        $clinicIds = array_keys($clinics);

        $dailyRecords = MedicalRecord::select('tanggal_periksa', 'clinic_id', DB::raw('COUNT(*) as total'))
            ->whereBetween('tanggal_periksa', [$startDate, $endDate])
            ->groupBy('tanggal_periksa', 'clinic_id')
            ->orderBy('tanggal_periksa')
            ->get()
            ->groupBy(function($item) {
                return Carbon::parse($item->tanggal_periksa)->format('Y-m-d');
            });

        $reportData = [];
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $dateKey = $currentDate->format('Y-m-d');
            $row = ['date' => $dateKey, 'total' => 0];

            foreach ($clinicIds as $clinicId) {
                $row['clinic_'.$clinicId] = 0;
            }

            if (isset($dailyRecords[$dateKey])) {
                foreach ($dailyRecords[$dateKey] as $record) {
                    $clinicId = $record->clinic_id;
                    if (isset($row['clinic_'.$clinicId])) {
                        $row['clinic_'.$clinicId] = $record->total;
                        $row['total'] += $record->total;
                    }
                }
            }
            $reportData[] = $row;
            $currentDate->addDay();
        }

        return [$reportData, $summary];
    }
    
    protected function getTopDiagnosaReport($startDate, $endDate)
    {
        // 1. Query Diagnosa Terbanyak
        $diagnosaRecords = MedicalRecord::select('diagnosa', DB::raw('COUNT(*) as total'))
            ->whereBetween('tanggal_periksa', [$startDate, $endDate])
            ->groupBy('diagnosa')
            ->orderByDesc('total')
            ->take(10) // Ambil 10 besar
            ->get();
        
        $reportData = $diagnosaRecords->map(function ($item, $index) {
            return [
                'rank' => $index + 1,
                'diagnosa' => $item->diagnosa,
                'total' => $item->total,
            ];
        })->toArray();

        // 2. Summary
        $summary['total_records'] = MedicalRecord::whereBetween('tanggal_periksa', [$startDate, $endDate])->count();
        $summary['unique_diagnosa'] = MedicalRecord::whereBetween('tanggal_periksa', [$startDate, $endDate])->distinct('diagnosa')->count();
        
        return [$reportData, $summary];
    }

    protected function getObatUsageReport($startDate, $endDate)
    {
        // 1. Query Penggunaan Obat Terbanyak
        $usageRecords = Prescription::select('medication_id', DB::raw('SUM(jumlah) as total_used'))
            ->whereHas('medicalRecord', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal_periksa', [$startDate, $endDate]);
            })
            ->groupBy('medication_id')
            ->orderByDesc('total_used')
            ->take(10)
            ->get();

        $medicationIds = $usageRecords->pluck('medication_id');
        $medications = Medication::whereIn('id', $medicationIds)->pluck('nama_obat', 'id');

        $reportData = $usageRecords->map(function ($item, $index) use ($medications) {
            return [
                'rank' => $index + 1,
                'medication_id' => $item->medication_id,
                'obat' => $medications[$item->medication_id] ?? 'Obat Dihapus',
                'total_used' => number_format($item->total_used, 0, ',', '.'),
            ];
        })->toArray();

        // 2. Summary
        $summary['total_prescriptions'] = Prescription::whereHas('medicalRecord', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('tanggal_periksa', [$startDate, $endDate]);
        })->count();
        
        $summary['total_obat_unik'] = Medication::count();
        
        return [$reportData, $summary];
    }
}