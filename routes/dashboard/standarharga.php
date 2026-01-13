<?php

use App\Http\Controllers\StandarHargaSatuan\DataSSHController;
use App\Http\Controllers\StandarhargaSatuan\KelompokSatuanHargaController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('standarHarga')->group(function () {

    // ===================================================
    // KELOMPOK SATUAN HARGA ROUTES
    // ===================================================
    Route::get('/kel_satuan_harga', [KelompokSatuanHargaController::class, 'index'])->name('kelompok_satuan_harga.index');
    Route::post('/kel_satuan_harga', [KelompokSatuanHargaController::class, 'store'])->name('kelompok_satuan_harga.store');
    Route::get('/kel_satuan_harga/{id}/edit', [KelompokSatuanHargaController::class, 'edit'])->name('kelompok_satuan_harga.edit');
    Route::put('/kel_satuan_harga/{id}', [KelompokSatuanHargaController::class, 'update'])->name('kelompok_satuan_harga.update');
    Route::delete('/kel_satuan_harga/{id}', [KelompokSatuanHargaController::class, 'destroy'])->name('kelompok_satuan_harga.destroy');
    Route::post('/kel_satuan_harga/bulk-delete', [KelompokSatuanHargaController::class, 'bulkDelete'])->name('kelompok_satuan_harga.bulk-delete');

    // AJAX Routes
    Route::get('/kelompok-satuan-harga/get-by-tipe', [KelompokSatuanHargaController::class, 'getByTipe'])->name('kelompok_satuan_harga.get-by-tipe');
    Route::get('/kelompok-satuan-harga/get-by-tahun', [KelompokSatuanHargaController::class, 'getByTahun'])->name('kelompok_satuan_harga.get-by-tahun');
    Route::post('/kel_satuan_harga/{id}/toggle-active', [KelompokSatuanHargaController::class, 'toggleActive'])->name('kelompok_satuan_harga.toggle-active');

    // ===================================================
    // DATA SSH ROUTES (NEW)
    // ===================================================
    Route::get('/data_ssh', [DataSSHController::class, 'index'])->name('data_ssh.index');
    Route::post('/data_ssh', [DataSSHController::class, 'store'])->name('data_ssh.store');
    Route::get('/data_ssh/{id}/edit', [DataSSHController::class, 'edit'])->name('data_ssh.edit');
    Route::put('/data_ssh/{id}', [DataSSHController::class, 'update'])->name('data_ssh.update');
    Route::delete('/data_ssh/{id}', [DataSSHController::class, 'destroy'])->name('data_ssh.destroy');
    Route::post('/data_ssh/bulk-delete', [DataSSHController::class, 'bulkDelete'])->name('data_ssh.bulk-delete');

    // AJAX Routes for SSH
    Route::post('/data_ssh/{id}/toggle-lock', [DataSSHController::class, 'toggleLock'])->name('data_ssh.toggle-lock');
    Route::get('/data-ssh/get-by-kelompok', [DataSSHController::class, 'getByKelompok'])->name('data_ssh.get-by-kelompok');
});
