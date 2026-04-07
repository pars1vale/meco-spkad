<?php

use App\Http\Controllers\Pembiayaan\PenerimaanController;
use App\Http\Controllers\Pembiayaan\PengeluaranController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('pembiayaan')->name('pembiayaan.')->group(function () {

    Route::middleware('auth')->prefix('penerimaan')->name('penerimaan.')->group(function () {
        Route::get('/', [PenerimaanController::class, 'index'])->name('index');
        Route::get('/get-data', [PenerimaanController::class, 'getDataIndex'])->name('getData');
        Route::get('/{id_skpd}/rincian', [PenerimaanController::class, 'rincian'])->name('rincian');
        Route::get('/{id_skpd}/rincian/get-data', [PenerimaanController::class, 'getDataRincian'])->name('rincian.getData');
        Route::post('/{id_skpd}/store', [PenerimaanController::class, 'store'])->name('store');
        Route::get('/{id_skpd}/{id}/edit', [PenerimaanController::class, 'edit'])->name('edit');
        Route::put('/{id_skpd}/{id}/update', [PenerimaanController::class, 'update'])->name('update');
        Route::delete('/{id_skpd}/{id}/destroy', [PenerimaanController::class, 'destroy'])->name('destroy');
        Route::post('/{id_skpd}/bulk-delete', [PenerimaanController::class, 'bulkDelete'])->name('bulk-delete');
    });

    Route::middleware('auth')->prefix('pengeluaran')->name('pengeluaran.')->group(function () {
        Route::get('/', [PengeluaranController::class, 'index'])->name('index');
        Route::get('/get-data', [PengeluaranController::class, 'getDataIndex'])->name('getData');
        Route::get('/{id_skpd}/rincian', [PengeluaranController::class, 'rincian'])->name('rincian');
        Route::get('/{id_skpd}/rincian/get-data', [PengeluaranController::class, 'getDataRincian'])->name('rincian.getData');
        Route::post('/{id_skpd}/store', [PengeluaranController::class, 'store'])->name('store');
        Route::get('/{id_skpd}/{id}/edit', [PengeluaranController::class, 'edit'])->name('edit');
        Route::put('/{id_skpd}/{id}/update', [PengeluaranController::class, 'update'])->name('update');
        Route::delete('/{id_skpd}/{id}/destroy', [PengeluaranController::class, 'destroy'])->name('destroy');
        Route::post('/{id_skpd}/bulk-delete', [PengeluaranController::class, 'bulkDelete'])->name('bulk-delete');
    });
});
