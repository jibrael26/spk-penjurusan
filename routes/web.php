<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// ==========================================
// 1. LANDING PAGE (Publik / Belum Login)
// ==========================================
Route::get('/', function () {
    return view('welcome');
})->name('landing');


// ==========================================
// 2. PANEL USER / SISWA (Butuh Login & Verifikasi)
// ==========================================
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard User
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Manajemen Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Fitur Tes Penjurusan (Assessment)
    Route::get('/tes-penjurusan', [AssessmentController::class, 'index'])->name('assessment.index');
    Route::post('/tes-penjurusan/simpan', [AssessmentController::class, 'store'])->name('assessment.store');
    Route::get('/tes-penjurusan/hasil/{id}', [AssessmentController::class, 'showResult'])->name('assessment.result');

});


// ==========================================
// 3. PANEL ADMIN (Butuh Login & Status Admin)
// ==========================================
// Penggunaan prefix('admin') dan name('admin.') akan otomatis menambahkan 
// awalan "admin/" pada URL dan "admin." pada nama rute di dalamnya.
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard & Data Master
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/data-siswa', [AdminController::class, 'dataSiswa'])->name('siswa');
    
    // Fitur Manajemen Pertanyaan (Manual & AI)
    Route::get('/questions', [AdminController::class, 'questions'])->name('pertanyaan');
    Route::post('/questions', [AdminController::class, 'storeQuestion'])->name('questions.store');
    Route::post('/questions/generate', [AdminController::class, 'generateQuestions'])->name('questions.generate');
    
    // Fitur Aksi Tambahan: Edit & Hapus Pertanyaan
    Route::get('/questions/{id}/edit', [AdminController::class, 'editQuestion'])->name('questions.edit');
    Route::delete('/questions/{id}', [AdminController::class, 'destroyQuestion'])->name('questions.destroy');

});

require __DIR__.'/auth.php';