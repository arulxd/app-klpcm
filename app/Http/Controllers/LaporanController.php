<?php

namespace App\Http\Controllers;

use App\Models\Analisis;
use App\Models\Ruangan;
use App\Models\Dokter;
use App\Models\MasterFormulir;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index()
    {
        $ruangan = Ruangan::orderBy('nama')->get();
        $dokter = Dokter::orderBy('nama')->get();
        $formulir = MasterFormulir::orderBy('nama')->get();

        return view('laporan.index', compact('ruangan', 'dokter', 'formulir'));
    }

    public function cetak(Request $request)
    {
        $request->validate([
            'tgl_awal' => 'required|date',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_awal',
            'jenis_laporan' => 'required|in:umum,ruangan,dokter,formulir,rekap_ruangan',
        ]);

        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;
        $jenis = $request->jenis_laporan;

        // --- KHUSUS REKAP JUMLAH PER RUANGAN (BEDA FILE VIEW) ---
        if ($jenis == 'rekap_ruangan') {
            $query = Ruangan::query();
            if ($request->ruangan_id != 'all') {
                $query->where('id', $request->ruangan_id);
            }
            $data = $query->withCount([
                'rekam_medis as jumlah_tidak_lengkap' => function($q) use ($tgl_awal, $tgl_akhir) {
                    $q->whereHas('analisis', function($a) use ($tgl_awal, $tgl_akhir) {
                        $a->whereBetween('tgl_analisis', [$tgl_awal, $tgl_akhir])->where('status', 'tidak_lengkap');
                    });
                },
                'rekam_medis as jumlah_total_analisis' => function($q) use ($tgl_awal, $tgl_akhir) {
                    $q->whereHas('analisis', function($a) use ($tgl_awal, $tgl_akhir) {
                        $a->whereBetween('tgl_analisis', [$tgl_awal, $tgl_akhir]);
                    });
                }
            ])->get();

            $pdf = Pdf::loadView('laporan.pdf_rekap_ruangan', compact('data', 'tgl_awal', 'tgl_akhir'));
            $pdf->setPaper('a4', 'portrait');
            return $pdf->stream('Laporan-Rekap-Ruangan.pdf');
        }

        // --- LAPORAN DETAIL (UMUM, DOKTER, RUANGAN, FORMULIR) ---
        $query = Analisis::with([
            'rekam_medis.pasien', 
            'rekam_medis.dokter', 
            'rekam_medis.ruangan', 
            'detail_analisis.formulir', 
            'detail_analisis.kriteria'
        ])->whereBetween('tgl_analisis', [$tgl_awal, $tgl_akhir]);
        
        $judul_tambahan = "";

        if ($jenis == 'ruangan') {
             if ($request->ruangan_id != 'all') {
                $query->whereHas('rekam_medis', function($q) use ($request) {
                    $q->where('ruangan_id', $request->ruangan_id);
                });
                $r = Ruangan::find($request->ruangan_id);
                $judul_tambahan = "Filter Ruangan: " . ($r ? $r->nama : 'Semua');
             }
        }
        elseif ($jenis == 'dokter') {
             $doc = Dokter::find($request->dokter_id);
             $query->whereHas('rekam_medis', function($q) use ($request) {
                $q->where('dokter_id', $request->dokter_id);
             });
             $judul_tambahan = "Filter Dokter: " . ($doc ? $doc->nama : '-');
        }
        elseif ($jenis == 'formulir') {
             if ($request->formulir_id == 'all') {
                $judul_tambahan = "Rekapitulasi Semua Ketidaklengkapan";
                $query->where('status', 'tidak_lengkap');
             } else {
                $form = MasterFormulir::find($request->formulir_id);
                $judul_tambahan = "Ketidaklengkapan: " . ($form ? $form->nama : '-');
                $query->whereHas('detail_analisis', function($q) use ($request) {
                    $q->where('formulir_id', $request->formulir_id);
                });
             }
        }

        $data = $query->latest('tgl_analisis')->get();

        // Hitung Statistik Dasar
        $total = $data->count();
        $lengkap = $data->where('status', 'lengkap')->count();
        $tidak_lengkap = $data->where('status', 'tidak_lengkap')->count();
        $persentase = $total > 0 ? round(($lengkap / $total) * 100, 1) : 0;

        $pdf = Pdf::loadView('laporan.pdf_view', compact(
            'data', 'tgl_awal', 'tgl_akhir', 
            'total', 'lengkap', 'tidak_lengkap', 'persentase',
            'judul_tambahan', 
            'jenis' // <--- PENTING: Kita kirim jenis laporan ke View
        ));
        
        $pdf->setPaper('a4', 'landscape'); 
        return $pdf->stream('Laporan-KLPCM.pdf');
    }
}