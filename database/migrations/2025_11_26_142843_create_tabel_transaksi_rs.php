<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 4. TABEL PASIEN (Dulu: patients)
        Schema::create('pasien', function (Blueprint $table) {
            $table->id();
            $table->string('no_rm')->unique(); // Dulu: mr_number
            $table->string('nik')->nullable();
            $table->string('nama');
            $table->date('tgl_lahir'); // Dulu: dob
            $table->enum('jenis_kelamin', ['L', 'P']); // Dulu: gender
            $table->timestamps();
        });

        // 5. TABEL REKAM MEDIS (Dulu: medical_records)
        Schema::create('rekam_medis', function (Blueprint $table) {
            $table->id();
            // Perhatikan constrained() merujuk ke nama tabel baru (singular)
            $table->foreignId('pasien_id')->constrained('pasien')->onDelete('cascade');
            $table->foreignId('dokter_id')->constrained('dokter'); 
            $table->foreignId('ruangan_id')->constrained('ruangan');   
            
            $table->string('no_registrasi')->unique(); // Dulu: visit_number
            $table->dateTime('tgl_masuk'); // Dulu: admission_date
            $table->dateTime('tgl_pulang'); // Dulu: discharge_date
            
            $table->enum('cara_masuk', ['igd', 'poli'])->default('igd');
            $table->enum('jenis_bayar', ['umum', 'bpjs', 'asuransi'])->default('bpjs');
            $table->dateTime('tgl_berkas_kembali')->nullable(); // Dulu: file_returned_at
            
            $table->timestamps();
        });

        // 6. TABEL ANALISIS (Dulu: analyses)
        Schema::create('analisis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rekam_medis_id')->constrained('rekam_medis')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users'); // User tetap tabel users
            
            $table->date('tgl_analisis');
            $table->enum('status', ['lengkap', 'tidak_lengkap'])->default('tidak_lengkap');
            $table->dateTime('deadline_revisi')->nullable(); 
            
            $table->timestamps();
        });

        // 7. TABEL DETAIL ANALISIS (Dulu: analysis_details)
        Schema::create('detail_analisis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('analisis_id')->constrained('analisis')->onDelete('cascade');
            $table->foreignId('item_analisis_id')->constrained('item_analisis'); 
            
            $table->boolean('is_lengkap')->default(false); // Dulu: is_complete
            $table->text('catatan')->nullable(); // Dulu: notes
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_analisis');
        Schema::dropIfExists('analisis');
        Schema::dropIfExists('rekam_medis');
        Schema::dropIfExists('pasien');
    }
};