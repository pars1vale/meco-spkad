<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserRoleController;
use Illuminate\Support\Facades\Route;

// ======================== PUBLIC ======================== //
Route::get('/', [App\Http\Controllers\LandingController::class, 'index'])->name('landing.index');

// ⚠️ HAPUS Auth::routes() — sudah digantikan oleh routes/auth/auth.php

// ======================== AUTH + TAHUN ANGGARAN ======================== //
Route::group(['middleware' => ['auth', 'tahun.anggaran']], function () {

    // Dashboard
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Manajemen Role & Permission
    Route::resource('roles', RoleController::class)
        ->middleware('permission:role.view|role.create|role.edit|role.delete');

    // Assign Role ke User
    Route::prefix('user-roles')->name('user-roles.')->middleware('permission:user.edit')->group(function () {
        Route::get('/', [UserRoleController::class, 'index'])->name('index');
        Route::get('{user}/edit', [UserRoleController::class, 'edit'])->name('edit');
        Route::put('{user}', [UserRoleController::class, 'update'])->name('update');
    });

    // Sub-route files
    require __DIR__.'/dashboard/referensi.php';
    require __DIR__.'/dashboard/rkpd.php';
    require __DIR__.'/dashboard/standarharga.php';
    require __DIR__.'/dashboard/pengaturan.php';
    require __DIR__.'/dashboard/pendapatan.php';
    require __DIR__.'/dashboard/pembiayaan.php';
});

// ======================== AUTH ROUTES ======================== //
require __DIR__.'/auth/auth.php';

// ======================== HELPERS ======================== //
function set_active($route)
{
    if (is_array($route)) {
        return in_array(Request::path(), $route) ? 'active' : '';
    }

    return Request::path() == $route ? 'active' : '';
}
