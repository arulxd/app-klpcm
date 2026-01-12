<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    // 1. LIST DATA
    public function index(Request $request)
    {
        $query = Ruangan::query();

        // Pencarian (Kode atau Nama)
        if ($request->has('search') && $request->search != null) {
            $search = $request->search;
            $query->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('kode', 'like', '%' . $search . '%');
        }

        $ruangan = $query->orderBy('nama')->paginate(10)->appends(['search' => $request->search]);

        return view('ruangan.index', compact('ruangan'));
    }

    // 2. FORM TAMBAH
    public function create()
    {
        return view('ruangan.create');
    }

    // 3. SIMPAN DATA
    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|unique:ruangan,kode|max:10',
            'nama' => 'required|string|max:255',
            'tipe' => 'required|in:rawat_inap,rawat_jalan,igd,ok,penunjang',
        ]);

        // Simpan (Kode otomatis di-uppercase biar rapi)
        Ruangan::create([
            'kode' => strtoupper($request->kode),
            'nama' => $request->nama,
            'tipe' => $request->tipe
        ]);

        return redirect()->route('ruangan.index')->with('success', 'Data Ruangan berhasil ditambahkan!');
    }

    // 4. FORM EDIT
    public function edit($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        return view('ruangan.edit', compact('ruangan'));
    }

    // 5. UPDATE DATA
    public function update(Request $request, $id)
    {
        $ruangan = Ruangan::findOrFail($id);

        $request->validate([
            // Ignore ID sendiri saat cek unique kode
            'kode' => 'required|string|max:10|unique:ruangan,kode,' . $ruangan->id,
            'nama' => 'required|string|max:255',
            'tipe' => 'required|in:rawat_inap,rawat_jalan,igd,ok,penunjang',
        ]);

        $ruangan->update([
            'kode' => strtoupper($request->kode),
            'nama' => $request->nama,
            'tipe' => $request->tipe
        ]);

        return redirect()->route('ruangan.index')->with('success', 'Data Ruangan berhasil diperbarui!');
    }

    // 6. HAPUS DATA
    public function destroy($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        $ruangan->delete();

        return redirect()->route('ruangan.index')->with('success', 'Data Ruangan berhasil dihapus!');
    }
}