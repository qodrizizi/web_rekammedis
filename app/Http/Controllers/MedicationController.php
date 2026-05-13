<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class MedicationController extends Controller
{
    /**
     * Menampilkan halaman manajemen obat dengan statistik.
     */
    public function index(Request $request)
    {
        $query = Medication::query();

        // Fitur Filter: Pencarian (Nama Obat / Kode Obat)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_obat', 'like', "%{$search}%")
                  ->orWhere('kode_obat', 'like', "%{$search}%");
            });
        }

        // Fitur Filter: Status (Stok Kritis / Akan Kadaluarsa)
        if ($request->filled('filter')) {
            $filter = $request->filter;
            if ($filter === 'stok_kritis') {
                $query->whereColumn('stok', '<=', 'stok_minimum');
            } elseif ($filter === 'kadaluarsa') {
                $query->whereNotNull('tanggal_kedaluwarsa')
                      ->where('tanggal_kedaluwarsa', '<=', Carbon::now()->addDays(60));
            }
        }

        $medications = $query->latest()->paginate(10)->withQueryString();

        $stokKritisCount = Medication::whereColumn('stok', '<=', 'stok_minimum')->count();
        
        $kadaluarsaCount = Medication::whereNotNull('tanggal_kedaluwarsa')
                                     ->where('tanggal_kedaluwarsa', '<=', Carbon::now()->addDays(60))
                                     ->count();
        
        $totalJenisObat = Medication::count();

        // Asumsi: 'disetujui' berarti resep dari dokter yang menunggu diambil/disiapkan
        $resepMenungguCount = Appointment::where('status', 'disetujui')->count(); 
        
        // Mengambil log stok terbaru (dari tabel activity_logs)
        $stockLogs = \App\Models\ActivityLog::with('user')
                        ->where('deskripsi', 'like', '%Obat%')
                        ->latest('waktu')
                        ->take(50)
                        ->get();

        // Master Data Satuan (Bisa diubah jadi dari Database jika dibutuhkan nanti)
        $masterSatuan = [
            'Tablet', 'Kapsul', 'Kaplet', 'Pil', 'Bungkus', 'Sachet',
            'Sirup / Botol', 'Suspensi / Botol', 'Drops / Botol', 
            'Salep / Tube', 'Krim / Tube', 'Gel / Tube',
            'Ampul', 'Vial', 'Infus', 'Suppositoria', 'Ovula',
            'Strip', 'Blister', 'Box', 'Karton', 'Pcs', 'Roll', 'Fles'
        ];
        sort($masterSatuan);

        return view('shared.obat', compact(
            'medications', 
            'stokKritisCount', 
            'kadaluarsaCount', 
            'totalJenisObat', 
            'resepMenungguCount',
            'stockLogs',
            'masterSatuan'
        ));
    }

    /**
     * Menyimpan data obat baru dari modal.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_obat' => 'required|string|max:20|unique:medications,kode_obat',
            'nama_obat' => 'required|string|max:150',
            'satuan' => 'required|string|max:50',
            'stok' => 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:0',
            'harga' => 'required|numeric|min:0',
            'tanggal_kedaluwarsa' => 'nullable|date',
        ]);

        $med = Medication::create($validated);

        // Catat Log
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'aksi' => 'Tambah Obat',
            'deskripsi' => "Menambahkan obat baru: {$med->nama_obat} (Stok awal: {$med->stok})",
            'waktu' => now()
        ]);

        return redirect()->back()->with('success', 'Obat baru berhasil ditambahkan.');
    }

    /**
     * Update data obat dari modal.
     */
    public function update(Request $request, Medication $medication)
    {
        $validated = $request->validate([
            'kode_obat' => [
                'required', 'string', 'max:20',
                Rule::unique('medications')->ignore($medication->id),
            ],
            'nama_obat' => 'required|string|max:150',
            'satuan' => 'required|string|max:50',
            'stok' => 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:0',
            'harga' => 'required|numeric|min:0',
            'tanggal_kedaluwarsa' => 'nullable|date',
        ]);

        $stokLama = $medication->stok;
        $medication->update($validated);
        
        // Catat Log jika stok berubah
        if ($stokLama != $medication->stok) {
            \App\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'aksi' => 'Update Stok Obat',
                'deskripsi' => "Memperbarui stok obat: {$medication->nama_obat} dari {$stokLama} menjadi {$medication->stok}",
                'waktu' => now()
            ]);
        } else {
             \App\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'aksi' => 'Update Data Obat',
                'deskripsi' => "Memperbarui data obat: {$medication->nama_obat}",
                'waktu' => now()
            ]);
        }

        return redirect()->back()->with('success', 'Data obat berhasil diperbarui.');
    }

    /**
     * Hapus data obat.
     */
    public function destroy(Medication $medication)
    {
        try {
            $namaObat = $medication->nama_obat;
            $medication->delete();
            
            \App\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'aksi' => 'Hapus Obat',
                'deskripsi' => "Menghapus data obat: {$namaObat}",
                'waktu' => now()
            ]);

            return redirect()->back()->with('success', 'Data obat berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menghapus obat: ' . $e->getMessage()]);
        }
    }
}