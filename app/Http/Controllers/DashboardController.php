<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Analisis;
use Carbon\Carbon;

class DashboardController extends Controller
{
   public function index()
    {
        $today = Carbon::today();

        // 1. AMBIL DATA (Tambahkan relation 'ruangan')
        $analisis_hari_ini = Analisis::with(['rekam_medis.dokter', 'rekam_medis.ruangan'])
                                ->whereDate('tgl_analisis', $today)
                                ->get();

        // 2. HITUNG STATISTIK UTAMA
        $total_berkas  = $analisis_hari_ini->count();
        $total_lengkap = $analisis_hari_ini->where('status', 'lengkap')->count();
        $total_revisi  = $analisis_hari_ini->where('status', 'tidak_lengkap')->count();

        // 3. (BARU) HITUNG PERFORMA PER RUANGAN
        $per_ruangan = $analisis_hari_ini->groupBy(function($item) {
            return $item->rekam_medis->ruangan->nama ?? 'Lain-lain';
        })->map(function($group) {
            $total = $group->count();
            $lengkap = $group->where('status', 'lengkap')->count();
            $persen = $total > 0 ? round(($lengkap / $total) * 100) : 0;
            
            return [
                'total' => $total,
                'lengkap' => $lengkap,
                'persen' => $persen
            ];
        })->sortByDesc('persen'); // Urutkan dari yang paling rajin (100%)

        // 4. CHART DATA
        $chart_pie = [$total_lengkap, $total_revisi];

        $top_dokter = $analisis_hari_ini->where('status', 'tidak_lengkap')
            ->groupBy(function($item) {
                return $item->rekam_medis->dokter->nama ?? 'Tanpa Nama';
            })
            ->map(function ($group) { return $group->count(); })
            ->sortDesc()->take(5);

        $chart_bar_names  = $top_dokter->keys()->toArray();
        $chart_bar_counts = $top_dokter->values()->toArray();

        return view('dashboard', compact(
            'total_berkas', 'total_lengkap', 'total_revisi',
            'chart_pie', 'chart_bar_names', 'chart_bar_counts',
            'per_ruangan' // <--- Jangan lupa kirim variabel baru ini
        ));
    }
}