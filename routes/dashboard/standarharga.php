<?php

use App\Http\Controllers\StandarhargaSatuan\KelompokSatuanHargaController;
use App\Http\Controllers\StandarhargaSatuan\StandarHargaController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('standarHarga')->group(function () {

    Route::get('/kel_satuan_harga', [KelompokSatuanHargaController::class, 'index'])->name('kelompok_satuan_harga.index');
    Route::post('/kel_satuan_harga', [KelompokSatuanHargaController::class, 'store'])->name('kelompok_satuan_harga.store');
    Route::get('/kel_satuan_harga/{id}/edit', [KelompokSatuanHargaController::class, 'edit'])->name('kelompok_satuan_harga.edit');
    Route::put('/kel_satuan_harga/{id}', [KelompokSatuanHargaController::class, 'update'])->name('kelompok_satuan_harga.update');
    Route::delete('/kel_satuan_harga/{id}', [KelompokSatuanHargaController::class, 'destroy'])->name('kelompok_satuan_harga.destroy');
    Route::post('/kel_satuan_harga/bulk-delete', [KelompokSatuanHargaController::class, 'bulkDelete'])->name('kelompok_satuan_harga.bulk-delete');

    Route::get('/kelompok-satuan-harga/get-by-tipe', [KelompokSatuanHargaController::class, 'getByTipe'])->name('kelompok_satuan_harga.get-by-tipe');
    Route::get('/kelompok-satuan-harga/get-by-tahun', [KelompokSatuanHargaController::class, 'getByTahun'])->name('kelompok_satuan_harga.get-by-tahun');
    Route::post('/kel_satuan_harga/{id}/toggle-active', [KelompokSatuanHargaController::class, 'toggleActive'])->name('kelompok_satuan_harga.toggle-active');

    Route::get('/', [StandarHargaController::class, 'index'])->name('standar_harga.index');
    Route::post('/', [StandarHargaController::class, 'store'])->name('standar_harga.store');
    Route::get('/{id}/edit', [StandarHargaController::class, 'edit'])->name('standar_harga.edit');
    Route::put('/{id}', [StandarHargaController::class, 'update'])->name('standar_harga.update');
    Route::delete('/{id}', [StandarHargaController::class, 'destroy'])->name('standar_harga.destroy');
    Route::post('/bulk-delete', [StandarHargaController::class, 'bulkDelete'])->name('standar_harga.bulk-delete');

    Route::post('/{id}/add-rekening', [StandarHargaController::class, 'addRekening'])->name('standar_harga.add-rekening');
    Route::delete('/{id}/remove-rekening', [StandarHargaController::class, 'removeRekening'])->name('standar_harga.remove-rekening');
});
