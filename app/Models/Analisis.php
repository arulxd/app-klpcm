<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Analisis extends Model
{
    protected $table = 'analisis';
    protected $guarded = ['id'];

    // Relasi ke Rekam Medis (Sudah ada sebelumnya)
    public function rekam_medis()
    {
        return $this->belongsTo(RekamMedis::class, 'rekam_medis_id');
    }

    // --- TAMBAHAN BARU (Solusi Error) ---
    // Relasi ke Detail Centang (One to Many)
    public function detail_analisis()
    {
        return $this->hasMany(DetailAnalisis::class, 'analisis_id');
    }
}