<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. TABEL DOCTORS (DPJP)
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->nullable()->unique();
            $table->string('name'); 
            $table->string('specialty')->nullable(); 
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. TABEL UNITS (RUANGAN)
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); 
            $table->string('name');
            $table->enum('type', ['rawat_inap', 'rawat_jalan', 'igd', 'ok', 'penunjang']);
            $table->timestamps();
        });

        // 3. TABEL ANALYSIS ITEMS (INSTRUMEN CHECKLIST)
        Schema::create('analysis_items', function (Blueprint $table) {
            $table->id();
            $table->enum('category', ['identifikasi', 'autentikasi', 'pelaporan', 'pencatatan']);
            $table->string('form_name'); 
            $table->string('component'); 
            $table->timestamps();
        });

        // Update tabel Users bawaan untuk tambah kolom 'role'
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('staff')->after('email'); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('analysis_items');
        Schema::dropIfExists('units');
        Schema::dropIfExists('doctors');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};