<?php

namespace App\Http\Controllers;

use App\Models\Medication; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MedicationController extends Controller
{
    /**
     * Menampilkan daftar semua data obat.
     */
    public function index()
    {
        // Ambil semua data obat dari database, diurutkan berdasarkan nama
        $medications = Medication::orderBy('nama_obat')->get();
        
        // View disesuaikan dengan rute '/admin/obat'
        return view('admin.obat', compact('medications'));
    }

    /**
     * Menyimpan data obat baru.
     */
    public function store(Request $request)
    {
        // PENTING: Validasi 'code' sebagai kode_obat yang unik
        $request->validate([
            'code' => 'required|string|max:100|unique:medications,kode_obat',
            'name' => 'required|string|max:150',
            'category' => 'nullable|string|max:100',
            'stock' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'exp_date' => 'required|date',
            'description' => 'nullable|string', // Validasi untuk form, meskipun kolomnya mungkin tidak terpakai
            'unit' => 'required|string|max:50', 
        ]);

        Medication::create([
            'kode_obat' => $request->code, // <-- Pastikan ini memetakan input 'code' ke kolom DB 'kode_obat'
            'nama_obat' => $request->name,
            'kategori' => $request->category,
            'stok' => $request->stock,
            'harga' => $request->unit_price,
            'tanggal_kedaluwarsa' => $request->exp_date,
            'satuan' => $request->unit,
            'deskripsi' => $request->description,
        ]);

        // Redirect ke route 'admin.obat'
        return redirect()->route('admin.obat')->with('success', 'Data obat berhasil ditambahkan!');
    }

    /**
     * Memperbarui data obat.
     */
    public function update(Request $request, $id)
    {
        $medication = Medication::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:150',
            'category' => 'nullable|string|max:100',
            'stock' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'exp_date' => 'required|date',
            'description' => 'nullable|string',
            'unit' => 'required|string|max:50', 
        ]);
        
        $medication->update([
            'nama_obat' => $request->name,
            'kategori' => $request->category,
            'stok' => $request->stock,
            'harga' => $request->unit_price,
            'tanggal_kedaluwarsa' => $request->exp_date,
            'satuan' => $request->unit,
            'deskripsi' => $request->description,
        ]);

        // Redirect ke route 'admin.obat'
        return redirect()->route('admin.obat')->with('success', 'Data obat berhasil diupdate!');
    }

    /**
     * Menghapus data obat.
     */
    public function destroy($id)
    {
        $medication = Medication::findOrFail($id);
        $medication->delete();

        // Redirect ke route 'admin.obat'
        return redirect()->route('admin.obat')->with('success', 'Data obat berhasil dihapus!');
    }
}