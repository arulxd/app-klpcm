<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. TABEL DOKTER (Dulu: doctors)
        Schema::create('dokter', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->nullable()->unique();
            $table->string('nama'); 
            $table->string('spesialis')->nullable(); // Dulu: specialty
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. TABEL RUANGAN (Dulu: units)
        Schema::create('ruangan', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique(); 
            $table->string('nama');
            $table->enum('tipe', ['rawat_inap', 'rawat_jalan', 'igd', 'ok', 'penunjang']);
            $table->timestamps();
        });

        // 3. TABEL ITEM ANALISIS (Dulu: analysis_items)
        Schema::create('item_analisis', function (Blueprint $table) {
            $table->id();
            $table->enum('kategori', ['identifikasi', 'autentikasi', 'pelaporan', 'pencatatan']);
            $table->string('nama_formulir'); // Dulu: form_name
            $table->string('komponen'); // Dulu: component
            $table->timestamps();
        });

        // Update User (tetap sama, hanya kolom role)
        // Kita cek dulu apakah kolom role sudah ada, kalau belum baru tambah
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('staff')->after('email'); 
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('item_analisis');
        Schema::dropIfExists('ruangan');
        Schema::dropIfExists('dokter');
    }
};