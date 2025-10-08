<?php

use App\Http\Controllers\Shs\KelompokStandarHargaController;
use App\Http\Controllers\Shs\StandarHargaController;
use App\Http\Controllers\Shs\SatuanController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('standarHarga')->group(function () {

    Route::get('/satuan', [SatuanController::class, 'index'])->name('satuan.index');
    Route::post('/satuan', [SatuanController::class, 'store'])->name('satuan.store');
    Route::get('/satuan/{id}/edit', [SatuanController::class, 'edit'])->name('satuan.edit');
    Route::put('/satuan/{id}', [SatuanController::class, 'update'])->name('satuan.update');
    Route::delete('/satuan/{id}', [SatuanController::class, 'destroy'])->name('satuan.destroy');
    Route::post('/satuan/bulk-delete', [SatuanController::class, 'bulkDelete'])->name('satuan.bulk-delete');

    Route::get('/kel_satuan_harga', [KelompokStandarHargaController::class, 'index'])->name('kelompok_satuan_harga.index');
    Route::post('/kel_satuan_harga', [KelompokStandarHargaController::class, 'store'])->name('kelompok_satuan_harga.store');
    Route::get('/kel_satuan_harga/{id}/edit', [KelompokStandarHargaController::class, 'edit'])->name('kelompok_satuan_harga.edit');
    Route::put('/kel_satuan_harga/{id}', [KelompokStandarHargaController::class, 'update'])->name('kelompok_satuan_harga.update');
    Route::delete('/kel_satuan_harga/{id}', [KelompokStandarHargaController::class, 'destroy'])->name('kelompok_satuan_harga.destroy');
    Route::post('/kel_satuan_harga/bulk-delete', [KelompokStandarHargaController::class, 'bulkDelete'])->name('kelompok_satuan_harga.bulk-delete');
    Route::get('/kelompok-standar-harga/get-by-tipe', [KelompokStandarHargaController::class, 'getByTipe'])->name('kelompok_satuan_harga.get-by-tipe');

    Route::get('/', [StandarHargaController::class, 'index'])->name('standar_harga.index');
    Route::post('/', [StandarHargaController::class, 'store'])->name('standar_harga.store');
    Route::get('/{id}/edit', [StandarHargaController::class, 'edit'])->name('standar_harga.edit');
    Route::put('/{id}', [StandarHargaController::class, 'update'])->name('standar_harga.update');
    Route::delete('/{id}', [StandarHargaController::class, 'destroy'])->name('standar_harga.destroy');
    Route::post('/bulk-delete', [StandarHargaController::class, 'bulkDelete'])->name('standar_harga.bulk-delete');

    // Routes untuk manage rekening belanja di standar harga
    Route::post('/{id}/add-rekening', [StandarHargaController::class, 'addRekening'])->name('standar_harga.add-rekening');
    Route::delete('/{id}/remove-rekening', [StandarHargaController::class, 'removeRekening'])->name('standar_harga.remove-rekening');
});
