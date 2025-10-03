<?php

use App\Http\Controllers\Shs\SatuanController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('shs')->group(function () {

    Route::get('/satuan', [SatuanController::class, 'index'])->name('satuan.index');
    Route::post('/satuan', [SatuanController::class, 'store'])->name('satuan.store');
    Route::get('/satuan/{id}/edit', [SatuanController::class, 'edit'])->name('satuan.edit');
    Route::put('/satuan/{id}', [SatuanController::class, 'update'])->name('satuan.update');
    Route::delete('/satuan/{id}', [SatuanController::class, 'destroy'])->name('satuan.destroy');
    Route::post('/satuan/bulk-delete', [SatuanController::class, 'bulkDelete'])->name('satuan.bulk-delete');
});
