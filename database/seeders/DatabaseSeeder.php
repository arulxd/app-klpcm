<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // ---------------------------------------------------------
        // BAGIAN 1: DATA MASTER (REFERENSI)
        // ---------------------------------------------------------

        // 1. USER ADMIN
        // Cek dulu biar gak error duplicate entry kalau dijalankan db:seed saja
        if(DB::table('users')->where('email', 'admin@rs.com')->doesntExist()) {
            DB::table('users')->insert([
                'name' => 'Petugas Rekam Medis',
                'email' => 'admin@rs.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'created_at' => now(),
            ]);
        }

        // 2. RUANGAN
        $ruangan = [
            ['kode' => 'ANGGREK', 'nama' => 'R. Inap Anggrek (VIP)', 'tipe' => 'rawat_inap'],
            ['kode' => 'MAWAR', 'nama' => 'R. Inap Mawar (Kelas 1)', 'tipe' => 'rawat_inap'],
            ['kode' => 'IGD', 'nama' => 'Instalasi Gawat Darurat', 'tipe' => 'igd'],
            ['kode' => 'POLI-PD', 'nama' => 'Poli Penyakit Dalam', 'tipe' => 'rawat_jalan'],
        ];
        DB::table('ruangan')->insertOrIgnore($ruangan, ['kode']); 
        // insertOrIgnore: kalau kode sudah ada, lewati (aman untuk seeding ulang)

        // 3. DOKTER
        $dokter = [
            ['nama' => 'dr. Budi Santoso, Sp.PD', 'spesialis' => 'Penyakit Dalam', 'nik' => 'D001', 'is_active' => true],
            ['nama' => 'dr. Siti Aminah, Sp.A', 'spesialis' => 'Anak', 'nik' => 'D002', 'is_active' => true],
            ['nama' => 'dr. Andi Wijaya, Sp.B', 'spesialis' => 'Bedah', 'nik' => 'D003', 'is_active' => true],
        ];
        DB::table('dokter')->insertOrIgnore($dokter, ['nik']);

        // 4. MASTER FORMULIR (Daftar Nama Formulir)
        $forms = [
            ['nama' => 'Resume Medis'],
            ['nama' => 'Laporan Operasi'],
            ['nama' => 'Catatan Perkembangan Pasien (CPPT)'],
            ['nama' => 'Informed Consent'],
            ['nama' => 'Asesmen Awal IGD'],
            ['nama' => 'Asesmen Awal Medis Rawat Jalan'],
            ['nama' => 'Asesmen Awal Keperawatan'],
            ['nama' => 'Ringkasan Pulang'],
        ];
        // Kosongkan tabel master dulu biar ID-nya urut dari 1 lagi
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('master_formulir')->truncate();
        DB::table('master_kriteria')->truncate();
        DB::table('detail_analisis')->truncate();
        DB::table('analisis')->truncate();
        DB::table('rekam_medis')->truncate();
        DB::table('pasien')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        DB::table('master_formulir')->insert($forms);

        // 5. MASTER KRITERIA (Item a, b, c, e Update Anda)
        $kriteria = [
            // A. Kelengkapan Identifikasi
            ['kategori' => 'Identifikasi Pasien', 'item' => 'Nama Pasien'],
            ['kategori' => 'Identifikasi Pasien', 'item' => 'Nomor Rekam Medis'],
            ['kategori' => 'Identifikasi Pasien', 'item' => 'Tanggal Lahir'],
            ['kategori' => 'Identifikasi Pasien', 'item' => 'Jenis Kelamin'],

            // B. Kelengkapan Laporan Penting
            ['kategori' => 'Laporan Penting', 'item' => 'Catatan Perawat'],
            ['kategori' => 'Laporan Penting', 'item' => 'Catatan Dokter'],
            ['kategori' => 'Laporan Penting', 'item' => 'Catatan PPA Lain'],

            // C. Autentifikasi
            ['kategori' => 'Autentifikasi', 'item' => 'Nama Terang'],
            ['kategori' => 'Autentifikasi', 'item' => 'Tanda Tangan'],
            ['kategori' => 'Autentifikasi', 'item' => 'Tanggal dan Jam'],

            // E. Pencatatan Baik
            ['kategori' => 'Pencatatan', 'item' => 'Dokumen Kosong/Tidak Terisi'],
            ['kategori' => 'Pencatatan', 'item' => 'Pembetulan Kesalahan (Coretan)'],
        ];
        DB::table('master_kriteria')->insert($kriteria);


        // ---------------------------------------------------------
        // BAGIAN 2: DATA TRANSAKSI (DUMMY PASIEN)
        // ---------------------------------------------------------

        // 6. PASIEN
        $pasienId = DB::table('pasien')->insertGetId([
            'no_rm' => '00-12-34',
            'nik' => '3500000000000001',
            'nama' => 'Bpk. Contoh Pasien',
            'tgl_lahir' => '1980-05-20',
            'jenis_kelamin' => 'L',
            'created_at' => now(),
        ]);

        // 7. REKAM MEDIS
        $rmId = DB::table('rekam_medis')->insertGetId([
            'pasien_id' => $pasienId,
            'dokter_id' => 1, // dr. Budi
            'ruangan_id' => 1, // Anggrek
            'no_registrasi' => 'REG-20231001-001',
            'tgl_masuk' => Carbon::now()->subDays(5),
            'tgl_pulang' => Carbon::now()->subDays(2),
            'cara_masuk' => 'igd',
            'jenis_bayar' => 'bpjs',
            'tgl_berkas_kembali' => Carbon::now(),
            'created_at' => now(),
        ]);

        // 8. ANALISIS (Contoh yang TIDAK LENGKAP)
        $analisisId = DB::table('analisis')->insertGetId([
            'rekam_medis_id' => $rmId,
            'user_id' => 1, // Admin
            'tgl_analisis' => now(),
            'status' => 'tidak_lengkap',
            'deadline_revisi' => Carbon::now()->addDays(2),
            'created_at' => now(),
        ]);

        // 9. DETAIL ANALISIS (Contoh Temuan Ketidaklengkapan)
        // Skenario: Resume Medis (ID 1) -> Tanda Tangan (ID 9) Tidak Ada
        // Kita ambil ID Form dan ID Kriteria secara manual (asumsi urutan insert)
        
        $formResume = DB::table('master_formulir')->where('nama', 'Resume Medis')->value('id');
        $itemTtd = DB::table('master_kriteria')->where('item', 'Tanda Tangan')->value('id');
        $itemNama = DB::table('master_kriteria')->where('item', 'Nama Terang')->value('id');

        DB::table('detail_analisis')->insert([
            [
                'analisis_id' => $analisisId,
                'formulir_id' => $formResume,
                'kriteria_id' => $itemTtd,
                'is_lengkap' => false, 
                'catatan' => 'Tanda tangan dokter kosong'
            ],
            [
                'analisis_id' => $analisisId,
                'formulir_id' => $formResume,
                'kriteria_id' => $itemNama,
                'is_lengkap' => false, 
                'catatan' => 'Nama terang tidak terbaca'
            ],
        ]);
    }
}