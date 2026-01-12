<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 4. TABEL PATIENTS
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('mr_number')->unique(); 
            $table->string('nik')->nullable();
            $table->string('name');
            $table->date('dob'); 
            $table->enum('gender', ['L', 'P']);
            $table->timestamps();
        });

        // 5. TABEL MEDICAL RECORDS (KUNJUNGAN)
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained(); 
            $table->foreignId('unit_id')->constrained();   
            
            $table->string('visit_number')->unique(); 
            $table->dateTime('admission_date');
            $table->dateTime('discharge_date');
            
            // Kolom Tambahan Request Anda
            $table->enum('admission_source', ['igd', 'poli'])->default('igd');
            $table->enum('payment_type', ['umum', 'bpjs', 'asuransi'])->default('bpjs');
            $table->dateTime('file_returned_at')->nullable(); 
            
            $table->timestamps();
        });

        // 6. TABEL ANALYSES (HEADER)
        Schema::create('analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_record_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained(); 
            
            $table->date('analysis_date');
            $table->enum('status', ['lengkap', 'tidak_lengkap'])->default('tidak_lengkap');
            $table->dateTime('deadline_followup')->nullable(); 
            
            $table->timestamps();
        });

        // 7. TABEL ANALYSIS DETAILS (RINCIAN CHECKLIST)
        Schema::create('analysis_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('analysis_id')->constrained()->onDelete('cascade');
            $table->foreignId('analysis_item_id')->constrained(); 
            
            $table->boolean('is_complete')->default(false); 
            $table->text('notes')->nullable(); 
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('analysis_details');
        Schema::dropIfExists('analyses');
        Schema::dropIfExists('medical_records');
        Schema::dropIfExists('patients');
    }
};