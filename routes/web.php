<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\Auth\AdminController;

Route::get('/', function () {
    return view('back.layouts.app');
});

// Admin Auth Routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'login']);
    Route::get('/register', [AdminController::class, 'showRegistrationForm'])->name('admin.register');
    Route::post('/register', [AdminController::class, 'register']);
    Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});

// Protected Routes - Only accessible by admin
Route::middleware(['auth:admin'])->group(function () {
    // Mapel Routes
    Route::resource('mapel', MapelController::class);

    // Tugas Routes
    Route::resource('tugas', TugasController::class);
    Route::get('tugas/{tugas}/download', [TugasController::class, 'download'])->name('tugas.download');

    // Siswa Routes
    Route::resource('siswa', SiswaController::class);

    // Nilai Routes
    Route::resource('nilai', NilaiController::class);

    // Kelas Routes
    Route::resource('kelas', KelasController::class)->except(['edit']);
});

