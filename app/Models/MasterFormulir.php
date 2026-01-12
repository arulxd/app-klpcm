<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterFormulir extends Model
{
    use HasFactory;

    protected $table = 'master_formulir';
    
    // Pastikan 'kategori' masuk di sini
    protected $fillable = [
        'nama',
        'kategori'
    ];

    // Relasi ke Detail Analisis (untuk pengecekan saat hapus)
    public function detail_analisis()
    {
        return $this->hasMany(DetailAnalisis::class, 'formulir_id');
    }
}