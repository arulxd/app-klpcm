<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;

    // Pastikan nama tabel di database benar (biasanya 'kriteria' atau 'master_kriteria')
    protected $table = 'master_kriteria'; 
    protected $guarded = ['id'];
    
    // Pastikan di database ada kolom 'judul'. 
    // Jika di database namanya 'nama_masalah' atau 'deskripsi', 
    // Anda harus mengganti panggilannya di View PDF nanti.
}