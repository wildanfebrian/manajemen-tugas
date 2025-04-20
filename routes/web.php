<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\KelasController;

Route::get('/', function () {
    return view('back.layouts.app');
});

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

