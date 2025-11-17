<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class PoliklinikController extends Controller
{
    /**
     * READ: Menampilkan daftar semua Poliklinik (Index).
     */
    public function index()
    {
        $clinics = Clinic::latest()->paginate(10); 

        // Mengembalikan view poliklinik.blade.php
        return view('admin.poliklinik', compact('clinics'));
    }

    /**
     * CREATE: Menyimpan data Poliklinik baru (Store).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_poli' => 'required|string|max:100|unique:clinics,nama_poli',
            'deskripsi' => 'nullable|string',
        ]);

        Clinic::create($validated);

        return redirect()->route('admin.poliklinik')->with('success', 'Data Poliklinik berhasil ditambahkan.');
    }

    /**
     * UPDATE: Memperbarui data Poliklinik (Update).
     */
    public function update(Request $request, Clinic $poliklinik) 
    {
        $validated = $request->validate([
            'nama_poli' => [
                'required',
                'string',
                'max:100',
                Rule::unique('clinics', 'nama_poli')->ignore($poliklinik->id),
            ],
            'deskripsi' => 'nullable|string',
        ]);
        
        $poliklinik->update($validated);

        return redirect()->route('admin.poliklinik')->with('success', 'Data Poliklinik berhasil diperbarui.');
    }

    /**
     * DELETE: Menghapus data Poliklinik (Destroy).
     */
    public function destroy(Clinic $poliklinik)
    {
        // PENTING: Anda mungkin perlu menambahkan logic untuk mencegah penghapusan
        // jika ada medical record atau appointment yang masih terkait.
        // Untuk saat ini, kita biarkan penghapusan sederhana.
        $poliklinik->delete();

        return redirect()->route('admin.poliklinik')->with('success', 'Data Poliklinik berhasil dihapus.');
    }
}