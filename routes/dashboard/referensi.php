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
    Route::get('/urusan', [UrusanController::class, 'index'])->name('referensi.urusan.index');
    Route::post('/urusan', [UrusanController::class, 'store'])->name('urusan.store');
    Route::get('/urusan/{id}/edit', [UrusanController::class, 'edit'])->name('urusan.edit');
    Route::put('/urusan/{id}', [UrusanController::class, 'update'])->name('urusan.update');
    Route::delete('/urusan/{id}', [UrusanController::class, 'destroy'])->name('urusan.destroy');
    Route::post('/urusan/bulk-delete', [UrusanController::class, 'bulkDelete'])->name('urusan.bulk-delete');

    // Bidang Urusan
    Route::get('/bidang-urusan', [BidangUrusanController::class, 'index'])->name('referensi.bidang-urusan.index');
    Route::post('/bidang-urusan', [BidangUrusanController::class, 'store'])->name('bidang-urusan.store');
    Route::get('/bidang-urusan/{id}/edit', [BidangUrusanController::class, 'edit'])->name('bidang-urusan.edit');
    Route::put('/bidang-urusan/{id}', [BidangUrusanController::class, 'update'])->name('bidang-urusan.update');
    Route::delete('/bidang-urusan/{id}', [BidangUrusanController::class, 'destroy'])->name('bidang-urusan.destroy');
    Route::post('/bidang-urusan/bulk-delete', [BidangUrusanController::class, 'bulkDelete'])->name('bidang-urusan.bulk-delete');

    // Program
    Route::get('/program', [ProgramController::class, 'index'])->name('referensi.program.index');
    Route::post('/program', [ProgramController::class, 'store'])->name('program.store');
    Route::get('/program/{id}/edit', [ProgramController::class, 'edit'])->name('program.edit');
    Route::put('/program/{id}', [ProgramController::class, 'update'])->name('program.update');
    Route::delete('/program/{id}', [ProgramController::class, 'destroy'])->name('program.destroy');
    Route::post('/program/bulk-delete', [ProgramController::class, 'bulkDelete'])->name('program.bulk-delete');

    // Kegiatan
    Route::get('/kegiatan', [KegiatanController::class, 'index'])->name('referensi.kegiatan.index');
    Route::post('/kegiatan', [KegiatanController::class, 'store'])->name('kegiatan.store');
    Route::get('/kegiatan/{id}/edit', [KegiatanController::class, 'edit'])->name('kegiatan.edit');
    Route::put('/kegiatan/{id}', [KegiatanController::class, 'update'])->name('kegiatan.update');
    Route::delete('/kegiatan/{id}', [KegiatanController::class, 'destroy'])->name('kegiatan.destroy');
    Route::post('/kegiatan/bulk-delete', [KegiatanController::class, 'bulkDelete'])->name('kegiatan.bulk-delete');

    // Sub Kegiatan
    Route::get('/sub-kegiatan', [SubKegiatanController::class, 'index'])->name('referensi.sub-kegiatan.index');
    Route::post('/sub-kegiatan', [SubKegiatanController::class, 'store'])->name('sub-kegiatan.store');
    Route::get('/sub-kegiatan/{id}/edit', [SubKegiatanController::class, 'edit'])->name('sub-kegiatan.edit');
    Route::put('/sub-kegiatan/{id}', [SubKegiatanController::class, 'update'])->name('sub-kegiatan.update');
    Route::delete('/sub-kegiatan/{id}', [SubKegiatanController::class, 'destroy'])->name('sub-kegiatan.destroy');
    Route::post('/sub-kegiatan/bulk-delete', [SubKegiatanController::class, 'bulkDelete'])->name('sub-kegiatan.bulk-delete');

    // Akun
    Route::get('/akun', [AkunController::class, 'index'])
        ->name('referensi.akun.index');

    // Sumber Dana
    Route::get('/sumber-dana', [SumberDanaController::class, 'index'])
        ->name('referensi.sumber-dana.index');
});
