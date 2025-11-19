<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use App\Models\Appointment; // Untuk cek resep
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class PetugasMedicationController extends Controller
{
    /**
     * Menampilkan halaman manajemen obat dengan statistik.
     */
    public function index()
    {
        // 1. Ambil data obat utama dengan pagination
        $medications = Medication::latest()->paginate(10);

        // 2. Hitung Statistik untuk 4 box
        $stokKritisCount = Medication::whereRaw('stok <= stok_minimum')->count();
        
        $kadaluarsaCount = Medication::where('tanggal_kedaluwarsa', '!=', null)
                                     ->where('tanggal_kedaluwarsa', '<=', Carbon::now()->addDays(60))
                                     ->count();
        
        $totalJenisObat = Medication::count();

        // Asumsi: 'disetujui' berarti resep dari dokter 
        // yang menunggu diambil/disiapkan oleh apotek
        $resepMenungguCount = Appointment::where('status', 'disetujui')->count(); 

        return view('admin.obat', compact(
            'medications', 
            'stokKritisCount', 
            'kadaluarsaCount', 
            'totalJenisObat', 
            'resepMenungguCount'
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

        Medication::create($validated);

        return redirect()->route('admin.obat')->with('success', 'Obat baru berhasil ditambahkan.');
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

        $medication->update($validated);

        return redirect()->route('admin.obat')->with('success', 'Data obat berhasil diperbarui.');
    }

    /**
     * Hapus data obat.
     */
    public function destroy(Medication $medication)
    {
        try {
            $medication->delete();
            return redirect()->route('admin.obat')->with('success', 'Data obat berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menghapus obat: ' . $e->getMessage()]);
        }
    }
}