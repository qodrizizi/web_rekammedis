<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PetugasDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // 1. CARD STATISTIK UTAMA
        $stats = [
            // Hitung resep yang statusnya 'menunggu' atau 'disiapkan'
            'resep_pending' => MedicalRecord::whereIn('status_pengambilan_obat', ['menunggu', 'disiapkan'])
                                ->whereHas('prescriptions') // Pastikan memang ada obatnya
                                ->count(),
            
            // Hitung obat yang stoknya di bawah atau sama dengan stok minimum
            'stok_kritis' => Medication::whereColumn('stok', '<=', 'stok_minimum')->count(),

            // Total resep selesai hari ini
            'resep_selesai_today' => MedicalRecord::where('status_pengambilan_obat', 'selesai')
                                    ->whereDate('updated_at', $today)
                                    ->count(),

            // Total Item Obat di Database
            'total_item_obat' => Medication::count(),
        ];

        // 2. LIST ANTRIAN RESEP (Untuk tabel utama)
        // Mengambil 5 resep terlama yang belum selesai (Prioritas)
        $antrianResep = MedicalRecord::whereIn('status_pengambilan_obat', ['menunggu', 'disiapkan'])
            ->whereHas('prescriptions')
            ->with(['patient.user', 'doctor.user'])
            ->orderBy('created_at', 'asc') // Yang lama di atas
            ->take(5)
            ->get();

        // 3. LIST OBAT MENIPIS (Untuk sidebar kanan)
        // Mengambil 5 obat dengan stok paling sedikit
        $obatMenipis = Medication::whereColumn('stok', '<=', 'stok_minimum')
            ->orderBy('stok', 'asc')
            ->take(5)
            ->get();

        return view('petugas.dashboard', compact('stats', 'antrianResep', 'obatMenipis'));
    }
}