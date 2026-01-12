<?php

namespace App\Http\Controllers;

use App\Models\MasterFormulir;
use Illuminate\Http\Request;

class FormulirController extends Controller
{
    // 1. LIST DATA (DENGAN PAGINATION DINAMIS)
    public function index(Request $request)
    {
        $query = MasterFormulir::query();

        // Fitur Pencarian
        if ($request->has('search') && $request->search != null) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        // Fitur "Per Page" (Ambil dari dropdown, default 10)
        $perPage = $request->input('per_page', 10);

        // Validasi agar user tidak menginput angka aneh via URL
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        // Paginate dengan 'appends' agar filter tidak hilang saat pindah halaman
        $formulir = $query->orderBy('nama')
                          ->paginate($perPage)
                          ->appends(['search' => $request->search, 'per_page' => $perPage]);

        return view('formulir.index', compact('formulir'));
    }

    // 2. FORM TAMBAH
    public function create()
    {
        return view('formulir.create');
    }

    // 3. SIMPAN DATA
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:master_formulir,nama',
            'kategori' => 'required|in:manual,elektronik',
        ]);

        MasterFormulir::create([
            'nama' => $request->nama,
            'kategori' => $request->kategori
        ]);

        return redirect()->route('formulir.index')->with('success', 'Data Formulir berhasil ditambahkan!');
    }

    // 4. FORM EDIT
    public function edit($id)
    {
        $formulir = MasterFormulir::findOrFail($id);
        return view('formulir.edit', compact('formulir'));
    }

    // 5. UPDATE DATA
    public function update(Request $request, $id)
    {
        $formulir = MasterFormulir::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255|unique:master_formulir,nama,' . $formulir->id,
            'kategori' => 'required|in:manual,elektronik',
        ]);

        $formulir->update([
            'nama' => $request->nama,
            'kategori' => $request->kategori
        ]);

        return redirect()->route('formulir.index')->with('success', 'Data Formulir berhasil diperbarui!');
    }

    // 6. HAPUS DATA
    public function destroy($id)
    {
        $formulir = MasterFormulir::findOrFail($id);
        
        // Cek relasi ke detail analisis (Opsional: Cegah hapus jika dipakai)
        if ($formulir->detail_analisis()->exists()) {
             return redirect()->back()->with('error', 'Gagal hapus! Formulir ini sedang digunakan dalam data analisis.');
        }

        $formulir->delete();
        return redirect()->route('formulir.index')->with('success', 'Data Formulir berhasil dihapus!');
    }
}