<?php
use App\Http\Controllers\Rkpd\TahapPenjadwalanController;
use App\Http\Controllers\Rkpd\SubTahapController;
use App\Http\Controllers\Rkpd\JadwalController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('rkpd')->group(function () {
    // tahap penjadwalan
    Route::get('/tahap-penjadwalan', [TahapPenjadwalanController::class, 'index'])->name('rkpd.tahap-penjadwalan.index');
    Route::post('/tahap-penjadwalan', [TahapPenjadwalanController::class, 'store'])->name('tahap-penjadwalan.store');
    Route::get('/tahap-penjadwalan/{id}/edit', [TahapPenjadwalanController::class, 'edit'])->name('tahap-penjadwalan.edit');
    Route::put('/tahap-penjadwalan/{id}', [TahapPenjadwalanController::class, 'update'])->name('tahap-penjadwalan.update');
    Route::delete('/tahap-penjadwalan/{id}', [TahapPenjadwalanController::class, 'destroy'])->name('tahap-penjadwalan.destroy');
    Route::post('/tahap-penjadwalan/bulk-delete', [TahapPenjadwalanController::class, 'bulkDelete'])->name('tahap-penjadwalan.bulk-delete');

    // Sub Tahap
    Route::get('/sub-tahap', [SubTahapController::class, 'index'])->name('rkpd.sub-tahap.index');
    Route::post('/sub-tahap', [SubTahapController::class, 'store'])->name('rkpd.sub-tahap.store');
    Route::get('/sub-tahap/{id}/edit', [SubTahapController::class, 'edit'])->name('rkpd.sub-tahap.edit');
    Route::put('/sub-tahap/{id}', [SubTahapController::class, 'update'])->name('rkpd.sub-tahap.update');
    Route::delete('/sub-tahap/{id}', [SubTahapController::class, 'destroy'])->name('rkpd.sub-tahap.destroy');
    Route::post('/sub-tahap/bulk-delete', [SubTahapController::class, 'bulkDelete'])->name('rkpd.sub-tahap.bulk-delete');

    // Jadwal RKPD
    Route::get('/jadwal-rkpd', [JadwalController::class, 'index'])->name('rkpd.jadwal-rkpd.index');
    Route::post('/jadwal-rkpd', [JadwalController::class, 'store'])->name('rkpd.jadwal-rkpd.store');
    Route::get('/jadwal-rkpd/{id}/edit', [JadwalController::class, 'edit'])->name('rkpd.jadwal-rkpd.edit');
    Route::put('/jadwal-rkpd/{id}', [JadwalController::class, 'update'])->name('rkpd.jadwal-rkpd.update');
    Route::delete('/jadwal-rkpd/{id}', [JadwalController::class, 'destroy'])->name('rkpd.jadwal-rkpd.destroy');
    Route::post('/jadwal-rkpd/bulk-delete', [JadwalController::class, 'bulkDelete'])->name('rkpd.jadwal-rkpd.bulk-delete');
});
