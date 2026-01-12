<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Illuminate\Http\Request;

class PasienController extends Controller
{
    // 1. LIST DATA (Dengan Search & Pagination)
    public function index(Request $request)
    {
        $query = Pasien::query();

        // Fitur Pencarian: Bisa cari Nama ATAU No RM
        if ($request->has('search') && $request->search != null) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('no_rm', 'like', '%' . $search . '%');
            });
        }

        // Fitur "Per Page"
        $perPage = $request->input('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        // Ambil data, urutkan berdasarkan No RM (atau Nama)
        $pasien = $query->orderBy('no_rm', 'asc') 
                        ->paginate($perPage)
                        ->appends(['search' => $request->search, 'per_page' => $perPage]);

        return view('pasien.index', compact('pasien'));
    }

    // 2. FORM TAMBAH
    public function create()
    {
        return view('pasien.create');
    }

    // 3. SIMPAN DATA
    public function store(Request $request)
    {
        $request->validate([
            'no_rm' => 'required|string|unique:pasien,no_rm',
            'nama' => 'required|string|max:255',
            'nik' => 'nullable|numeric|digits:16', // Opsional
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'nullable|string',
        ]);

        Pasien::create($request->all());

        return redirect()->route('pasien.index')->with('success', 'Data Pasien berhasil ditambahkan!');
    }

    // 4. FORM EDIT
    public function edit($id)
    {
        $pasien = Pasien::findOrFail($id);
        return view('pasien.edit', compact('pasien'));
    }

    // 5. UPDATE DATA
    public function update(Request $request, $id)
    {
        $pasien = Pasien::findOrFail($id);

        $request->validate([
            'no_rm' => 'required|string|unique:pasien,no_rm,' . $pasien->id,
            'nama' => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        $pasien->update($request->all());

        return redirect()->route('pasien.index')->with('success', 'Data Pasien berhasil diperbarui!');
    }

    // 6. HAPUS DATA
    public function destroy($id)
    {
        $pasien = Pasien::findOrFail($id);
        
        // Cek Relasi: Jangan hapus pasien jika sudah pernah berobat (ada di rekam_medis)
        // Pastikan Anda sudah membuat relasi rekam_medis() di Model Pasien
        if ($pasien->rekam_medis()->exists()) {
             return redirect()->back()->with('error', 'Gagal hapus! Pasien ini memiliki riwayat kunjungan (Rekam Medis).');
        }

        $pasien->delete();
        return redirect()->route('pasien.index')->with('success', 'Data Pasien berhasil dihapus!');
    }
}