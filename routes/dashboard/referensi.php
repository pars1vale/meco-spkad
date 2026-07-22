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
    Route::get('/urusan', [UrusanController::class, 'index'])->name('referensi.urusan.index');
    Route::get('/urusan/get-data', [UrusanController::class, 'getData'])->name('referensi.urusan.getData');
    Route::post('/urusan', [UrusanController::class, 'store'])->name('referensi.urusan.store');
    Route::get('/urusan/{id}/edit', [UrusanController::class, 'edit'])->name('referensi.urusan.edit');
    Route::put('/urusan/{id}', [UrusanController::class, 'update'])->name('referensi.urusan.update');
    Route::delete('/urusan/{id}', [UrusanController::class, 'destroy'])->name('referensi.urusan.destroy');
    Route::post('/urusan/bulk-delete', [UrusanController::class, 'bulkDelete'])->name('referensi.urusan.bulk-delete');

    Route::get('/bidang-urusan', [BidangUrusanController::class, 'index'])->name('referensi.bidang-urusan.index');
    Route::get('/bidang-urusan/get-data', [BidangUrusanController::class, 'getData'])->name('referensi.bidang-urusan.getData');
    Route::post('/bidang-urusan', [BidangUrusanController::class, 'store'])->name('referensi.bidang-urusan.store');
    Route::get('/bidang-urusan/{id}/edit', [BidangUrusanController::class, 'edit'])->name('referensi.bidang-urusan.edit');
    Route::put('/bidang-urusan/{id}', [BidangUrusanController::class, 'update'])->name('referensi.bidang-urusan.update');
    Route::delete('/bidang-urusan/{id}', [BidangUrusanController::class, 'destroy'])->name('referensi.bidang-urusan.destroy');
    Route::post('/bidang-urusan/bulk-delete', [BidangUrusanController::class, 'bulkDelete'])->name('referensi.bidang-urusan.bulk-delete');

    Route::get('/program', [ProgramController::class, 'index'])->name('referensi.program.index');
    Route::get('/program/get-data', [ProgramController::class, 'getData'])->name('referensi.program.getData');
    Route::post('/program', [ProgramController::class, 'store'])->name('referensi.program.store');
    Route::get('/program/{id}/edit', [ProgramController::class, 'edit'])->name('referensi.program.edit');
    Route::put('/program/{id}', [ProgramController::class, 'update'])->name('referensi.program.update');
    Route::delete('/program/{id}', [ProgramController::class, 'destroy'])->name('referensi.program.destroy');
    Route::post('/program/bulk-delete', [ProgramController::class, 'bulkDelete'])->name('referensi.program.bulk-delete');

    Route::get('/kegiatan', [KegiatanController::class, 'index'])->name('referensi.kegiatan.index');
    Route::get('/kegiatan/get-data', [KegiatanController::class, 'getData'])->name('referensi.kegiatan.getData');
    Route::post('/kegiatan', [KegiatanController::class, 'store'])->name('referensi.kegiatan.store');
    Route::get('/kegiatan/{id}/edit', [KegiatanController::class, 'edit'])->name('referensi.kegiatan.edit');
    Route::put('/kegiatan/{id}', [KegiatanController::class, 'update'])->name('referensi.kegiatan.update');
    Route::delete('/kegiatan/{id}', [KegiatanController::class, 'destroy'])->name('referensi.kegiatan.destroy');
    Route::post('/kegiatan/bulk-delete', [KegiatanController::class, 'bulkDelete'])->name('referensi.kegiatan.bulk-delete');

    Route::get('/sub-kegiatan', [SubKegiatanController::class, 'index'])->name('referensi.sub-kegiatan.index');
    Route::get('sub-kegiatan/get-data', [SubKegiatanController::class, 'getData'])->name('referensi.sub-kegiatan.getData');
    Route::post('/sub-kegiatan', [SubKegiatanController::class, 'store'])->name('referensi.sub-kegiatan.store');
    Route::get('/sub-kegiatan/{id}/edit', [SubKegiatanController::class, 'edit'])->name('referensi.sub-kegiatan.edit');
    Route::put('/sub-kegiatan/{id}', [SubKegiatanController::class, 'update'])->name('referensi.sub-kegiatan.update');
    Route::delete('/sub-kegiatan/{id}', [SubKegiatanController::class, 'destroy'])->name('referensi.sub-kegiatan.destroy');
    Route::post('/sub-kegiatan/bulk-delete', [SubKegiatanController::class, 'bulkDelete'])->name('referensi.sub-kegiatan.bulk-delete');

    Route::get('/akun', [AkunController::class, 'index'])->name('referensi.akun.index');
    Route::get('/akun/get-data', [AkunController::class, 'getData'])->name('referensi.akun.getData');
    Route::get('/akun/{id}/detail', [AkunController::class, 'detail'])->name('referensi.akun.detail');
    Route::post('/akun', [AkunController::class, 'store'])->name('referensi.akun.store');
    Route::get('/akun/{id}/edit', [AkunController::class, 'edit'])->name('referensi.akun.edit');
    Route::put('/akun/{id}', [AkunController::class, 'update'])->name('referensi.akun.update');
    Route::delete('/akun/{id}', [AkunController::class, 'destroy'])->name('referensi.akun.destroy');
    Route::post('/akun/bulk-delete', [AkunController::class, 'bulkDelete'])->name('referensi.akun.bulk-delete');

    Route::get('/sumber-dana', [SumberDanaController::class, 'index'])->name('referensi.sumber-dana.index');
    Route::get('/sumber-dana/get-data', [SumberDanaController::class, 'getData'])->name('referensi.sumber-dana.getData');
    Route::post('/sumber-dana', [SumberDanaController::class, 'store'])->name('referensi.sumber-dana.store');
    Route::get('/sumber-dana/{id}/edit', [SumberDanaController::class, 'edit'])->name('referensi.sumber-dana.edit');
    Route::put('/sumber-dana/{id}', [SumberDanaController::class, 'update'])->name('referensi.sumber-dana.update');
    Route::delete('/sumber-dana/{id}', [SumberDanaController::class, 'destroy'])->name('referensi.sumber-dana.destroy');
    Route::post('/sumber-dana/bulk-delete', [SumberDanaController::class, 'bulkDelete'])->name('referensi.sumber-dana.bulk-delete');
});
