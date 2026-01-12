<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\RekamMedis;
use App\Models\Analisis;
use App\Models\DetailAnalisis;
use App\Models\MasterFormulir;
use App\Models\MasterKriteria;

class AnalisisController extends Controller
{
    // 1. DASHBOARD UTAMA (Statistik)
    public function dashboard()
    {
        // 1. Ambil Semua Data Analisis
        $all_data = Analisis::with(['rekam_medis.dokter'])->get();

        // 2. Data untuk DONUT CHART (Lengkap vs Revisi)
        $total_lengkap = $all_data->where('status', 'lengkap')->count();
        $total_revisi = $all_data->where('status', 'tidak_lengkap')->count();
        
        // 3. Data untuk BAR CHART (Top 5 Dokter Revisi Tertinggi)
        // Kita filter yang 'tidak_lengkap', kelompokkan per nama dokter, hitung jumlahnya, urutkan, ambil 5 teratas
        $top_dokters = $all_data->where('status', 'tidak_lengkap')
            ->groupBy('rekam_medis.dokter.nama')
            ->map(function ($group) {
                return $group->count();
            })
            ->sortDesc()
            ->take(5);

        // Pisahkan Nama Dokter dan Jumlahnya untuk dikirim ke Chart
        $dokter_names = $top_dokters->keys()->toArray();
        $dokter_counts = $top_dokters->values()->toArray();

        return view('dashboard', [
            'data_rm' => $all_data, // Untuk kartu statistik angka
            // Data Chart
            'chart_pie' => [$total_lengkap, $total_revisi],
            'chart_bar_names' => $dokter_names,
            'chart_bar_counts' => $dokter_counts
        ]);
    }

    // 2. HALAMAN DATA ANALISIS (Tabel Lengkap)
    public function index(Request $request)
    {
        $query = RekamMedis::with(['pasien', 'dokter', 'ruangan', 'analisis']);

        if ($request->has('search') && $request->search != null) {
            $keyword = $request->search;
            $query->whereHas('pasien', function($q) use ($keyword) {
                $q->where('nama', 'like', '%' . $keyword . '%')
                  ->orWhere('no_rm', 'like', '%' . $keyword . '%');
            });
        }

        $data = $query->latest('tgl_masuk')->paginate(10)->appends(['search' => $request->search]);
        return view('analisis.index', ['data_rm' => $data]);
    }

    // 3. FORM INPUT BARU
    public function create()
    {
        // Ambil Data Master Form & Kriteria
        $list_form = MasterFormulir::orderBy('nama')->get();
        $list_kriteria = MasterKriteria::all()->groupBy('kategori');

        // Ambil Data Pasien (Filter: Yang BELUM dianalisis)
        // Variable ini ($list_rm) yang tadi hilang penyebab error
        $list_rm = RekamMedis::with('pasien')
                    ->doesntHave('analisis') 
                    ->latest('tgl_masuk')
                    ->get();

        // Kirim ke View
        return view('analisis.create', [
            'list_rm'       => $list_rm,       // <--- INI WAJIB ADA
            'list_form'     => $list_form,
            'list_kriteria' => $list_kriteria
        ]);
    }

    // 4. SIMPAN DATA
    public function store(Request $request)
    {
        $request->validate([
            'rekam_medis_id' => 'required|exists:rekam_medis,id',
            'tgl_berkas_kembali' => 'required|date',
            'defects' => 'nullable|array',
            'defects.*.form_id' => 'required|exists:master_formulir,id',
            'defects.*.kriteria_id' => 'required|exists:master_kriteria,id',
        ]);

        DB::transaction(function () use ($request) {
            
            // Update Tanggal Kembali
            $rm = RekamMedis::findOrFail($request->rekam_medis_id);
            $rm->update(['tgl_berkas_kembali' => $request->tgl_berkas_kembali]);

            // Cek Status
            $has_defects = !empty($request->defects);
            $status_akhir = $has_defects ? 'tidak_lengkap' : 'lengkap';

            // Simpan Header
            $analisis = Analisis::create([
                'rekam_medis_id' => $request->rekam_medis_id,
                'user_id' => Auth::id(),
                'tgl_analisis' => now(),
                'status' => $status_akhir,
                'deadline_revisi' => $has_defects ? now()->addDays(2) : null,
            ]);

            // Simpan Detail
            if ($has_defects) {
                foreach ($request->defects as $defect) {
                    DetailAnalisis::create([
                        'analisis_id' => $analisis->id,
                        'formulir_id' => $defect['form_id'],
                        'kriteria_id' => $defect['kriteria_id'],
                        'is_lengkap' => false,
                        'catatan' => $defect['note'] ?? null
                    ]);
                }
            }
        });

        return redirect()->route('analisis.create')
            ->with('success', 'Data berhasil disimpan! Silakan lanjut input pasien berikutnya.');
    }

    // 5. DETAIL READ-ONLY
    public function show($id)
    {
        $analisis = Analisis::with(['rekam_medis.pasien', 'detail_analisis.formulir', 'detail_analisis.kriteria'])->findOrFail($id);
        return view('analisis.show', ['analisis' => $analisis]);
    }

    // 6. FORM EDIT
    public function edit($id)
    {
        $analisis = Analisis::with(['rekam_medis.pasien', 'detail_analisis'])->findOrFail($id);
        $list_form = MasterFormulir::orderBy('nama')->get();
        $list_kriteria = MasterKriteria::all()->groupBy('kategori');

        return view('analisis.edit', [
            'analisis' => $analisis,
            'list_form' => $list_form,
            'list_kriteria' => $list_kriteria
        ]);
    }

    // 7. UPDATE DATA
    public function update(Request $request, $id)
    {
        DB::transaction(function () use ($request, $id) {
            $analisis = Analisis::findOrFail($id);
            $has_defects = !empty($request->defects);
            $status_baru = $has_defects ? 'tidak_lengkap' : 'lengkap';

            $analisis->update([
                'user_id' => Auth::id(),
                'tgl_analisis' => now(),
                'status' => $status_baru,
                'deadline_revisi' => $has_defects ? now()->addDays(2) : null,
            ]);

            DetailAnalisis::where('analisis_id', $id)->delete();

            if ($has_defects) {
                foreach ($request->defects as $defect) {
                    DetailAnalisis::create([
                        'analisis_id' => $analisis->id,
                        'formulir_id' => $defect['form_id'],
                        'kriteria_id' => $defect['kriteria_id'],
                        'is_lengkap' => false,
                        'catatan' => $defect['note'] ?? null
                    ]);
                }
            }
        });
        return redirect()->route('analisis.index')->with('success', 'Revisi berhasil diperbarui!');
    }

    // 8. HAPUS DATA
    public function destroy($id)
    {
        $analisis = Analisis::findOrFail($id);
        $analisis->delete();
        return redirect()->route('analisis.index')->with('success', 'Data analisis dihapus!');
    }
}