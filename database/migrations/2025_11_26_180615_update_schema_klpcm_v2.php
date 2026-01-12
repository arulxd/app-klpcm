<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
{
    // 1. BUAT TABEL MASTER FORMULIR (Daftar 50 Form)
    Schema::create('master_formulir', function (Blueprint $table) {
        $table->id();
        $table->string('nama'); // Contoh: Resume Medis, Laporan Operasi
        $table->timestamps();
    });

    // 2. BUAT TABEL MASTER KRITERIA (Item Standar a,b,c,e)
    Schema::create('master_kriteria', function (Blueprint $table) {
        $table->id();
        $table->string('kategori'); // Identifikasi, Autentikasi, dll
        $table->string('item');     // Nama, Tanda Tangan, Jam, dll
        $table->timestamps();
    });

    // 3. UPDATE TABEL DETAIL ANALISIS (Transaksi)
    // Hapus tabel lama dulu biar bersih, buat baru dengan struktur baru
    Schema::dropIfExists('detail_analisis');
    
    Schema::create('detail_analisis', function (Blueprint $table) {
        $table->id();
        $table->foreignId('analisis_id')->constrained('analisis')->onDelete('cascade');
        
        // KUNCI PERUBAHAN DISINI: Kita simpan 2 ID terpisah
        $table->foreignId('formulir_id')->constrained('master_formulir');
        $table->foreignId('kriteria_id')->constrained('master_kriteria');
        
        $table->boolean('is_lengkap')->default(false);
        $table->string('catatan')->nullable(); // Opsional
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
