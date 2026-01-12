<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use Illuminate\Http\Request;

class DokterController extends Controller
{
    // 1. LIST DATA (DENGAN SEARCH & PAGINATION)
    public function index(Request $request)
    {
        $query = Dokter::query();

        // Fitur Pencarian (Cari Nama atau Spesialis)
        if ($request->has('search') && $request->search != null) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('spesialis', 'like', '%' . $search . '%'); // Asumsi ada kolom spesialis
            });
        }

        // Fitur "Per Page"
        $perPage = $request->input('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        // Ambil Data
        $dokter = $query->orderBy('nama', 'asc')
                        ->paginate($perPage)
                        ->appends(['search' => $request->search, 'per_page' => $perPage]);

        return view('dokter.index', compact('dokter'));
    }

    // 2. FORM TAMBAH
    public function create()
    {
        return view('dokter.create');
    }

    // 3. SIMPAN DATA
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'spesialis' => 'required|string|max:100', // Sesuaikan validasi
            'kode_dokter' => 'nullable|string|max:50',
        ]);

        Dokter::create($request->all());

        return redirect()->route('dokter.index')->with('success', 'Data Dokter berhasil ditambahkan!');
    }

    // 4. FORM EDIT
    public function edit($id)
    {
        $dokter = Dokter::findOrFail($id);
        return view('dokter.edit', compact('dokter'));
    }

    // 5. UPDATE DATA
    public function update(Request $request, $id)
    {
        $dokter = Dokter::findOrFail($id);
        
        $request->validate([
            'nama' => 'required|string|max:255',
            'spesialis' => 'required|string|max:100',
        ]);

        $dokter->update($request->all());

        return redirect()->route('dokter.index')->with('success', 'Data Dokter berhasil diperbarui!');
    }

    // 6. HAPUS DATA
    public function destroy($id)
    {
        $dokter = Dokter::findOrFail($id);
        
        // Cek relasi agar tidak error jika dokter sudah punya data pasien
        if ($dokter->rekam_medis()->exists()) {
             return redirect()->back()->with('error', 'Gagal hapus! Dokter ini sudah terdaftar dalam rekam medis pasien.');
        }

        $dokter->delete();
        return redirect()->route('dokter.index')->with('success', 'Data Dokter berhasil dihapus!');
    }
}