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
    Route::get('/dashboard', [AssessmentController::class, 'dashboard'])->name('dashboard');

    // Manajemen Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ==========================================
    // Fitur Tes Penjurusan (Assessment) - Multi-Step
    // ==========================================
    
    // Halaman utama asesmen / dashboard pengantar
    Route::get('/tes-penjurusan', [AssessmentController::class, 'index'])->name('assessment.index');
    Route::get('/tes-penjurusan/hasil/{id}', [AssessmentController::class, 'showResult'])->name('assessment.result');
    
    // Tahap 1: Tes Minat (Teori RIASEC)
    Route::get('/tes-penjurusan/fase-1', [AssessmentController::class, 'createFase1'])->name('assessment.fase1');
    Route::post('/tes-penjurusan/fase-1', [AssessmentController::class, 'storeFase1'])->name('assessment.storeFase1');

    // Tahap 2: Tes Bakat (Teori CHC) & Proses Kalkulasi Akhir
    Route::get('/tes-penjurusan/fase-2', [AssessmentController::class, 'createFase2'])->name('assessment.fase2');
    Route::post('/tes-penjurusan/fase-2', [AssessmentController::class, 'storeFase2'])->name('assessment.storeFase2');

    Route::get('/tes-penjurusan/fase-3', [App\Http\Controllers\AssessmentController::class, 'fase3'])->name('assessment.fase3');
    Route::post('/tes-penjurusan/fase-3', [App\Http\Controllers\AssessmentController::class, 'storeFase3'])->name('assessment.storeFase3');

});


// ==========================================
// 3. PANEL ADMIN (Butuh Login & Status Admin)
// ==========================================
// Penggunaan prefix('admin') dan name('admin.') akan otomatis menambahkan 
// awalan "admin/" pada URL dan "admin." pada nama rute di dalamnya.
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard & Data Master Siswa
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/data-siswa', [AdminController::class, 'dataSiswa'])->name('siswa');

    // Fitur Aksi Siswa: Update & Hapus Data Siswa
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    
    // Fitur Manajemen Pertanyaan (Manual & AI)
    Route::get('/questions', [AdminController::class, 'questions'])->name('pertanyaan');
    Route::post('/questions', [AdminController::class, 'storeQuestion'])->name('questions.store');
    Route::post('/questions/generate', [AdminController::class, 'generateQuestions'])->name('questions.generate');
    
    // Fitur Aksi Tambahan: Edit & Hapus Pertanyaan
    Route::get('/questions/{id}/edit', [AdminController::class, 'editQuestion'])->name('questions.edit');
    Route::put('/questions/{id}', [AdminController::class, 'updateQuestion'])->name('questions.update');
    Route::delete('/questions/{id}', [AdminController::class, 'destroyQuestion'])->name('questions.destroy');

    // Fitur Export Data Penjurusan
    Route::get('/export-assessment', [AdminController::class, 'exportAssessment'])->name('export.assessment');

});

require __DIR__.'/auth.php';