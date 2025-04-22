<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\Auth\AdminController;
use App\Http\Controllers\Auth\SiswaAuthController;
use App\Http\Controllers\SiswaSubmissionController;

// Default route - Shows welcome page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Default login route - Required for Laravel's auth middleware
Route::get('/login', function () {
    return redirect()->route('siswa.login');
})->name('login');

// Admin Auth Routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'login']);
    Route::get('/register', [AdminController::class, 'showRegistrationForm'])->name('admin.register');
    Route::post('/register', [AdminController::class, 'register']);
    Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Protected Routes - Only accessible by admin
    Route::middleware(['auth:admin'])->group(function () {
        // Kelas Routes
        Route::resource('kelas', KelasController::class)->except(['edit'])->names([
            'index' => 'admin.kelas.index',
            'create' => 'admin.kelas.create',
            'store' => 'admin.kelas.store',
            'show' => 'admin.kelas.show',
            'update' => 'admin.kelas.update',
            'destroy' => 'admin.kelas.destroy',
        ]);

        // Mapel Routes
        Route::resource('mapel', MapelController::class)->names([
            'index' => 'admin.mapel.index',
            'create' => 'admin.mapel.create',
            'store' => 'admin.mapel.store',
            'show' => 'admin.mapel.show',
            'update' => 'admin.mapel.update',
            'destroy' => 'admin.mapel.destroy',
        ]);

        // Siswa Routes
        Route::resource('siswa', SiswaController::class)->names([
            'index' => 'admin.siswa.index',
            'create' => 'admin.siswa.create',
            'store' => 'admin.siswa.store',
            'show' => 'admin.siswa.show',
            'update' => 'admin.siswa.update',
            'destroy' => 'admin.siswa.destroy',
        ]);

        // Tugas Routes
        Route::resource('tugas', TugasController::class)->names([
            'index' => 'admin.tugas.index',
            'create' => 'admin.tugas.create',
            'store' => 'admin.tugas.store',
            'show' => 'admin.tugas.show',
            'update' => 'admin.tugas.update',
            'destroy' => 'admin.tugas.destroy',
        ]);
        Route::get('tugas/{tugas}/download', [TugasController::class, 'download'])->name('admin.tugas.download');

        // Nilai Routes
        Route::resource('nilai', NilaiController::class)->names([
            'index' => 'admin.nilai.index',
            'create' => 'admin.nilai.create',
            'store' => 'admin.nilai.store',
            'show' => 'admin.nilai.show',
            'update' => 'admin.nilai.update',
            'destroy' => 'admin.nilai.destroy',
        ]);
        Route::get('nilai/{nilai}/download', [NilaiController::class, 'downloadSubmission'])->name('admin.nilai.download');
    });
});

// Siswa Auth Routes
Route::prefix('siswa')->group(function () {
    Route::get('/login', [SiswaAuthController::class, 'showLoginForm'])->name('siswa.login');
    Route::post('/login', [SiswaAuthController::class, 'login']);
    Route::get('/register', [SiswaAuthController::class, 'showRegistrationForm'])->name('siswa.register');
    Route::post('/register', [SiswaAuthController::class, 'register']);
    Route::post('/logout', [SiswaAuthController::class, 'logout'])->name('siswa.logout');
    
    // Protected Routes - Only accessible by siswa
    Route::middleware(['auth:siswa'])->group(function () {
        Route::get('/dashboard', [SiswaAuthController::class, 'dashboard'])->name('siswa.dashboard');
        
        // Tugas routes for siswa
        Route::get('/tugas', [SiswaSubmissionController::class, 'index'])->name('siswa.tugas.index');
        Route::get('/tugas/{tugas}', [SiswaSubmissionController::class, 'show'])->name('siswa.tugas.show');
        Route::get('/tugas/{tugas}/download', [SiswaSubmissionController::class, 'download'])->name('siswa.tugas.download');
        Route::post('/tugas/{tugas}/submit', [SiswaSubmissionController::class, 'submit'])->name('siswa.tugas.submit');
        Route::get('/submission/{id}/download', [SiswaSubmissionController::class, 'downloadSubmission'])->name('siswa.submission.download');
    });
});

