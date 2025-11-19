<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Medication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PetugasResepController extends Controller
{
    public function index()
    {
        // 1. Ambil Antrian (Filter SANGAT PENTING: status != selesai)
        // Pastikan 'where' ini ada agar data yang sudah selesai HILANG dari antrian
        $antrian = MedicalRecord::whereHas('prescriptions')
            ->where('status_pengambilan_obat', '!=', 'selesai') 
            ->with(['patient.user', 'doctor.user', 'prescriptions.medication'])
            ->orderBy('created_at', 'asc') 
            ->get();

        // 2. Ambil Riwayat Selesai Hari Ini
        $selesai = MedicalRecord::where('status_pengambilan_obat', 'selesai')
            ->whereDate('updated_at', Carbon::today())
            ->with(['patient.user', 'doctor.user'])
            ->latest('updated_at')
            ->get();

        // 3. Statistik
        $stats = [
            'menunggu' => $antrian->count(),
            'selesai_hari_ini' => $selesai->count(),
            'total_bulan_ini' => MedicalRecord::where('status_pengambilan_obat', 'selesai')
                                ->whereMonth('updated_at', Carbon::now()->month)
                                ->count(),
            'stok_kritis' => Medication::whereColumn('stok', '<=', 'stok_minimum')->count()
        ];

        return view('petugas.resep', compact('antrian', 'selesai', 'stats'));
    }

    // LOGIKA 1: Mulai Siapkan (Potong Stok Disini)
    public function process($id)
    {
        $record = MedicalRecord::with('prescriptions.medication')->findOrFail($id);

        // Cek agar stok tidak terpotong 2x
        if ($record->status_pengambilan_obat == 'disiapkan') {
            return redirect()->back()->with('error', 'Resep ini sedang dalam proses penyiapan.');
        }

        DB::beginTransaction();
        try {
            // Loop obat dan potong stok
            foreach ($record->prescriptions as $item) {
                $obat = $item->medication;
                
                if ($obat->stok < $item->jumlah) {
                    throw new \Exception("Stok obat {$obat->nama_obat} kurang! (Sisa: {$obat->stok})");
                }

                $obat->decrement('stok', $item->jumlah);
            }

            // Ubah status jadi 'disiapkan'
            $record->status_pengambilan_obat = 'disiapkan';
            $record->save(); // Pakai save() agar lebih pasti
            
            DB::commit();
            return redirect()->back()->with('success', 'Stok dipotong. Silakan siapkan obat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    // LOGIKA 2: Selesai (Hanya Ubah Status & Pindahkan Data)
    public function complete($id)
    {
        $record = MedicalRecord::findOrFail($id);

        // Validasi: Status harus 'disiapkan' dulu
        if ($record->status_pengambilan_obat != 'disiapkan') {
            return redirect()->back()->with('error', 'Klik tombol "Mulai Siapkan" terlebih dahulu.');
        }

        // PERBAIKAN UTAMA DISINI:
        // Kita paksa ubah status jadi 'selesai' dan simpan
        $record->status_pengambilan_obat = 'selesai';
        $record->save(); 

        return redirect()->back()->with('success', 'Resep selesai diserahkan. Data dipindah ke Riwayat.');
    }
}