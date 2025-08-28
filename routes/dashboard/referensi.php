<?php

use App\Http\Controllers\Referensi\AkunController;
use App\Http\Controllers\Referensi\BidangUrusanController;
use App\Http\Controllers\Referensi\ProgramController;
use App\Http\Controllers\Referensi\KegiatanController;
use App\Http\Controllers\Referensi\SubKegiatanController;
use App\Http\Controllers\Referensi\SumberDanaController;
use App\Http\Controllers\Referensi\UrusanController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('referensi')->group(function () {
    // urusan
    Route::get('/urusan', [UrusanController::class, 'index'])
        ->name('referensi.urusan.index');

    // Bidang Urusan
    Route::get('/bidang-urusan', [BidangUrusanController::class, 'index'])
        ->name('referensi.bidang-urusan.index');

    // Program
    Route::get('/program', [ProgramController::class, 'index'])
        ->name('referensi.program.index');

    // Kegiatan
    Route::get('/kegiatan', [KegiatanController::class, 'index'])
        ->name('referensi.kegiatan.index');

    // Sub Kegiatan
    Route::get('/sub-kegiatan', [SubKegiatanController::class, 'index'])
        ->name('referensi.sub-kegiatan.index');

    // Akun
    Route::get('/akun', [AkunController::class, 'index'])
        ->name('referensi.akun.index');

    // Sumber Dana
    Route::get('/sumber-dana', [SumberDanaController::class, 'index'])
        ->name('referensi.sumber-dana.index');
});
