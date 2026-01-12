<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailAnalisis extends Model
{
    use HasFactory;

    protected $table = 'detail_analisis';
    protected $guarded = ['id'];

    // Relasi ke Master Formulir
    public function formulir()
    {
        return $this->belongsTo(MasterFormulir::class, 'formulir_id');
    }

    // Relasi ke Master Kriteria (Item Masalah)
    public function kriteria()
    {
        // Pastikan nama Modelnya 'Kriteria' atau 'MasterKriteria' sesuai yang Anda buat
        // Di sini kita asumsikan namanya 'Kriteria'
        return $this->belongsTo(Kriteria::class, 'kriteria_id');
    }

    // Relasi balik ke Analisis Utama (Optional)
    public function analisis()
    {
        return $this->belongsTo(Analisis::class, 'analisis_id');
    }
}