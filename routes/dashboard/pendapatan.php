<?php

use App\Http\Controllers\Pendapatan\PendapatanController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('pendapatan')->name('pendapatan.')->group(function () {

    // ── Halaman index & AJAX endpoint-nya ─────────────────────────────
    Route::get('/', [PendapatanController::class, 'index'])->name('index');
    Route::get('/get-data', [PendapatanController::class, 'getDataIndex'])->name('getData');

    // ── Halaman rincian per SKPD & AJAX endpoint-nya ──────────────────
    Route::get('/{id_skpd}/rincian', [PendapatanController::class, 'rincian'])->name('rincian');
    Route::get('/{id_skpd}/rincian/get-data', [PendapatanController::class, 'getDataRincian'])->name('rincian.getData');

    // ── CRUD ───────────────────────────────────────────────────────────
    Route::get('/{id_skpd}/create', [PendapatanController::class, 'create'])->name('create');
    Route::post('/{id_skpd}/store', [PendapatanController::class, 'store'])->name('store');
    Route::get('/{id_skpd}/{id}/edit', [PendapatanController::class, 'edit'])->name('edit');
    Route::put('/{id_skpd}/{id}/update', [PendapatanController::class, 'update'])->name('update');
    Route::delete('/{id_skpd}/{id}/destroy', [PendapatanController::class, 'destroy'])->name('destroy');

    // ── Bulk delete (AJAX JSON) ────────────────────────────────────────
    Route::post('/{id_skpd}/bulk-delete', [PendapatanController::class, 'bulkDelete'])->name('bulk-delete');
});
