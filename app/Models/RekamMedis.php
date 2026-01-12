<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; // Pastikan import Carbon

class RekamMedis extends Model
{
    use HasFactory;
   protected $table = 'rekam_medis';
    protected $guarded = ['id'];

    // Relasi ke Pasien
    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }

    // Relasi ke Dokter
    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'dokter_id');
    }

    // Relasi ke Ruangan
    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id');
    }

    // Relasi ke Hasil Analisis
    public function analisis()
    {
        return $this->hasOne(Analisis::class, 'rekam_medis_id');
    }
    
    // Fitur Hitung Selisih Waktu (Accessor)
    public function getDurasiBerkasAttribute()
    {
        if (!$this->tgl_pulang || !$this->tgl_berkas_kembali) {
            return '-';
        }
        
        // Menggunakan Carbon untuk hitung jam
        $pulang = \Carbon\Carbon::parse($this->tgl_pulang);
        $kembali = \Carbon\Carbon::parse($this->tgl_berkas_kembali);
        $jam = $pulang->diffInHours($kembali);
        
        return $jam . ' Jam';
    }

    // --- RUMUS HITUNG 2x24 JAM (BARU) ---
    public function getStatusPengembalianAttribute()
    {
        // Jika data tanggal belum lengkap
        if (!$this->tgl_pulang || !$this->tgl_berkas_kembali) {
            return [
                'status' => 'pending',
                'label' => 'Belum Kembali',
                'color' => 'bg-gray-100 text-gray-500',
                'selisih' => '-'
            ];
        }

        $pulang = Carbon::parse($this->tgl_pulang);
        $kembali = Carbon::parse($this->tgl_berkas_kembali);

        // Hitung selisih jam
        $selisih_jam = $pulang->diffInHours($kembali, false); // false = biar bisa negatif kalau kembali sebelum pulang (aneh tapi mungkin)

        // Logika 2x24 jam = 48 Jam
        if ($selisih_jam > 48) {
            return [
                'status' => 'terlambat',
                'label' => 'TERLAMBAT',
                'color' => 'bg-red-100 text-red-700 border-red-200',
                'selisih' => $selisih_jam . ' Jam'
            ];
        } else {
            return [
                'status' => 'tepat_waktu',
                'label' => 'TEPAT WAKTU',
                'color' => 'bg-green-100 text-green-700 border-green-200',
                'selisih' => $selisih_jam . ' Jam'
            ];
        }
    }
}
