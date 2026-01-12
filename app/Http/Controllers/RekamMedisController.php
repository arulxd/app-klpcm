<?php

namespace App\Http\Controllers;

use App\Models\RekamMedis;
use Illuminate\Http\Request;

class RekamMedisController extends Controller
{
    // 1. LIST DATA (Dengan Search Relasi & Pagination)
    public function index(Request $request)
    {
        // Ambil data beserta relasinya agar hemat query (Eager Loading)
        $query = RekamMedis::with(['pasien', 'dokter', 'ruangan']);

        // Fitur Pencarian (Cari Nama Pasien ATAU No RM)
        if ($request->has('search') && $request->search != null) {
            $search = $request->search;
            
            // Cari di dalam relasi 'pasien'
            $query->whereHas('pasien', function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('no_rm', 'like', '%' . $search . '%');
            });
        }

        // Fitur "Per Page"
        $perPage = $request->input('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        // Urutkan berdasarkan tanggal pulang terbaru
        $rekam_medis = $query->orderBy('tgl_pulang', 'desc')
                             ->paginate($perPage)
                             ->appends(['search' => $request->search, 'per_page' => $perPage]);

        return view('rekam_medis.index', compact('rekam_medis'));
    }

    // 2. FORM TAMBAH
    public function create()
    {
        // Kirim data master untuk dropdown
        $pasien = \App\Models\Pasien::orderBy('nama')->get();
        $dokter = \App\Models\Dokter::orderBy('nama')->get();
        $ruangan = \App\Models\Ruangan::orderBy('nama')->get();
        
        return view('rekam_medis.create', compact('pasien', 'dokter', 'ruangan'));
    }

    // 3. SIMPAN DATA
    public function store(Request $request)
    {
        $request->validate([
            'pasien_id' => 'required|exists:pasien,id',
            'dokter_id' => 'required|exists:dokter,id',
            'ruangan_id' => 'required|exists:ruangan,id',
            'tgl_masuk' => 'required|date',
            'tgl_pulang' => 'required|date|after_or_equal:tgl_masuk',
        ]);

        RekamMedis::create($request->all());

        return redirect()->route('rekam_medis.index')->with('success', 'Data Kunjungan berhasil ditambahkan!');
    }

    // 4. FORM EDIT
    public function edit($id)
    {
        $rm = RekamMedis::findOrFail($id);
        $pasien = \App\Models\Pasien::orderBy('nama')->get();
        $dokter = \App\Models\Dokter::orderBy('nama')->get();
        $ruangan = \App\Models\Ruangan::orderBy('nama')->get();

        return view('rekam_medis.edit', compact('rm', 'pasien', 'dokter', 'ruangan'));
    }

    // 5. UPDATE DATA
    public function update(Request $request, $id)
    {
        $rm = RekamMedis::findOrFail($id);
        
        $request->validate([
            'pasien_id' => 'required|exists:pasien,id',
            'dokter_id' => 'required|exists:dokter,id',
            'ruangan_id' => 'required|exists:ruangan,id',
            'tgl_masuk' => 'required|date',
            'tgl_pulang' => 'required|date|after_or_equal:tgl_masuk',
        ]);

        $rm->update($request->all());

        return redirect()->route('rekam_medis.index')->with('success', 'Data Kunjungan berhasil diperbarui!');
    }

    // 6. HAPUS DATA
    public function destroy($id)
    {
        $rm = RekamMedis::findOrFail($id);
        
        // Cek jika sudah dianalisis, jangan dihapus sembarangan
        if ($rm->analisis()->exists()) {
             return redirect()->back()->with('error', 'Gagal hapus! Data ini sudah memiliki hasil analisis KLPCM.');
        }

        $rm->delete();
        return redirect()->route('rekam_medis.index')->with('success', 'Data Kunjungan berhasil dihapus!');
    }
}