<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::table('master_formulir', function (Blueprint $table) {
        // Tambah kolom Enum 'kategori' setelah kolom 'nama'
        $table->enum('kategori', ['manual', 'elektronik'])->default('manual')->after('nama');
    });
}

public function down()
{
    Schema::table('master_formulir', function (Blueprint $table) {
        $table->dropColumn('kategori');
    });
}
};
