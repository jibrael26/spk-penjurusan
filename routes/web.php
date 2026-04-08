<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/data-siswa', [AdminController::class, 'dataSiswa'])->name('admin.siswa');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // Route untuk menampilkan halaman tes
    Route::get('/tes-penjurusan', [AssessmentController::class, 'index'])->name('assessment.index');

    // Route untuk memproses jawaban tes
    Route::post('/tes-penjurusan/simpan', [AssessmentController::class, 'store'])->name('assessment.store');

    // Route untuk melihat hasil rekomendasi
    Route::get('/tes-penjurusan/hasil/{id}', [AssessmentController::class, 'showResult'])->name('assessment.result');

});

require __DIR__.'/auth.php';
