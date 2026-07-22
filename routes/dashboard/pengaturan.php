<?php

use App\Http\Controllers\Pengaturan\Profil\PerangkatDaerah\DataUnitController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('pengaturan')->group(function () {

    // Perangkat Daerah
    Route::get('/perangkat-daerah', [DataUnitController::class, 'index'])->name('pengaturan.perangkat-daerah.index');
    Route::post('/perangkat-daerah', [DataUnitController::class, 'store'])->name('perangkat-daerah.store');
    Route::post('/unit-skpd', [DataUnitController::class, 'unitskpdstore'])->name('unit-skpd.store');
    Route::get('/perangkat-daerah/{id}/edit', [DataUnitController::class, 'edit'])->name('perangkat-daerah.edit');
    Route::put('/perangkat-daerah/{id}', [DataUnitController::class, 'update'])->name('perangkat-daerah.update');
    Route::delete('/perangkat-daerah/{id}', [DataUnitController::class, 'destroy'])->name('perangkat-daerah.destroy');
    Route::post('/perangkat-daerah/bulk-delete', [DataUnitController::class, 'bulkDelete'])->name('perangkat-daerah.bulk-delete');

    // Role & Permission
    Route::prefix('akses')->name('pengaturan.akses.')->group(function () {
        Route::get('/role', [RoleController::class, 'index'])
            ->middleware('permission:role.view')
            ->name('role.index');

        Route::post('/role', [RoleController::class, 'store'])
            ->middleware('permission:role.create')
            ->name('role.store');

        Route::get('/role/{role}/edit', [RoleController::class, 'edit'])
            ->middleware('permission:role.edit')
            ->name('role.edit');

        Route::put('/role/{role}', [RoleController::class, 'update'])
            ->middleware('permission:role.edit')
            ->name('role.update');

        Route::delete('/role/{role}', [RoleController::class, 'destroy'])
            ->middleware('permission:role.delete')
            ->name('role.destroy');
    });
});
