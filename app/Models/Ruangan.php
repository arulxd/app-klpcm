<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    protected $table = 'ruangan';
    protected $guarded = ['id'];

    // --- TAMBAHKAN INI (SOLUSI ERROR) ---
    // Satu Ruangan bisa memiliki BANYAK Rekam Medis
    public function rekam_medis()
    {
        return $this->hasMany(RekamMedis::class, 'ruangan_id');
    }
    
}
