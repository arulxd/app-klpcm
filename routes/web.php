<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnalisisController; // Panggil Controller RS Kita
use App\Http\Controllers\LaporanController; 
use App\Http\Controllers\DashboardController; 

// 1. HALAMAN DEPAN
// Jika orang buka 'app-klpcm.test', langsung lempar ke halaman Login
Route::get('/', function () {
    return redirect()->route('login');
});

// 2. DASHBOARD (Diganti dengan Controller RS Kita)
// Middleware 'auth' artinya: "Cek dulu, user sudah login belum?"
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// 3. GRUP RUTE KHUSUS PETUGAS (Harus Login)
Route::middleware(['auth', 'verified'])->group(function () {
    
    // 1. DASHBOARD UTAMA (Menu Sendiri)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. GROUP KLPCM
    // Halaman Data Tabel (Submenu KLPCM)
    Route::get('/analisis/data', [AnalisisController::class, 'index'])->name('analisis.index');
    
    // Halaman Input (Submenu KLPCM)
    Route::get('/analisis/baru', [AnalisisController::class, 'create'])->name('analisis.create');
    Route::post('/analisis/simpan', [AnalisisController::class, 'store'])->name('analisis.store');

    // 1. Menampilkan Form Edit (GET)
Route::get('/analisis/{id}/edit', [AnalisisController::class, 'edit'])->name('analisis.edit');
// Melihat Detail (Read-Only)
Route::get('/analisis/{id}/detail', [AnalisisController::class, 'show'])->name('analisis.show');


// 2. Menyimpan Perubahan (PUT)
Route::put('/analisis/{id}/update', [AnalisisController::class, 'update'])->name('analisis.update');

// Hapus Data (DELETE)
Route::delete('/analisis/{id}/hapus', [AnalisisController::class, 'destroy'])->name('analisis.destroy');

    // Fitur Profil User (Bawaan Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
Route::post('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
});

// 4. PANGGIL RUTE AUTH (Login, Register, Logout)
require __DIR__.'/auth.php';

// Rute CRUD Dokter (Otomatis membuat index, create, store, edit, update, destroy)
Route::resource('dokter', \App\Http\Controllers\DokterController::class);

Route::resource('pasien', \App\Http\Controllers\PasienController::class);

Route::resource('rekam_medis', \App\Http\Controllers\RekamMedisController::class);

Route::resource('formulir', \App\Http\Controllers\FormulirController::class);

// Kita bungkus dengan middleware AdminOnly
Route::resource('users', \App\Http\Controllers\UserController::class)
    ->middleware(\App\Http\Middleware\AdminOnly::class);


Route::resource('ruangan', \App\Http\Controllers\RuanganController::class);