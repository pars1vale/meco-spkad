<?php


use App\Http\Controllers\Pengaturan\Profil\PerangkatDaerah\DataUnitController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('pengaturan')->group(function () {
    // Perangkat Daerah
    Route::get('/perangkat-daerah', [DataUnitController::class, 'index'])->name('pengaturan.perangkat-daerah.index');
    Route::post('/perangkat-daerah', [DataUnitController::class, 'store'])->name('perangkat-daerah.store');
    Route::post('/unit-skpd', [DataUnitController::class, 'unitskpdstore'])->name('unit-skpd.store');
    // Route::get('/perangkat-daerah/{id}/edit', [DataUnitController::class, 'edit'])->name('perangkat-daerah.edit');
    // Route::put('/perangkat-daerah/{id}', [DataUnitController::class, 'update'])->name('perangkat-daerah.update');
    // Route::delete('/perangkat-daerah/{id}', [DataUnitController::class, 'destroy'])->name('perangkat-daerah.destroy');
    // Route::post('/perangkat-daerah/bulk-delete', [DataUnitController::class, 'bulkDelete'])->name('perangkat-daerah.bulk-delete');

   
});
