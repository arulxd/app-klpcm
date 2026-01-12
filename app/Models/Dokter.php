<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    use HasFactory;

    protected $table = 'dokter'; // Pastikan nama tabel benar
    protected $guarded = [];

    // --- TAMBAHKAN FUNGSI INI ---
    public function rekam_medis()
    {
        // Hubungan: Satu Dokter memiliki Banyak Rekam Medis
        return $this->hasMany(RekamMedis::class, 'dokter_id');
    }
}