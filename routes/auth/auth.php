<?php

// use App\Http\Controllers\Auth\ConfirmPasswordController;
// use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
// use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\TahunAnggaranController;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'App\Http\Controllers\Auth'], function () {

    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'login')->name('login');
        Route::post('/login', 'authenticate')->name('authenticate');
        Route::get('/logout', 'logout')->name('logout');
    });

    Route::controller(RegisterController::class)->group(function () {
        Route::get('/register', 'register')->name('register');
        Route::post('/register', 'storeUser')->name('storeUser');
    });

    // Route::controller(ForgotPasswordController::class)->group(function () {
    //     Route::get('forget-password', 'getEmail')->name('forget-password');
    //     Route::post('forget-password', 'postEmail')->name('forget-password.post');
    // });
    // Route::controller(ResetPasswordController::class)->group(function () {
    //     Route::get('reset-password/{token}', 'getPassword')->name('reset-password');
    //     Route::post('password/update', 'updatePassword')->name('password.update');
    // });
    // Route::controller(ConfirmPasswordController::class)->group(function () {
    //     Route::get('confirm/password', 'confirmPassword')->name('confirm.password');
    // });
});

Route::middleware(['auth'])->group(function () {
    Route::controller(TahunAnggaranController::class)->group(function () {
        Route::get('/tahun-anggaran/pilih', 'pilih')->name('tahun-anggaran.pilih');
        Route::post('/tahun-anggaran/simpan', 'simpan')->name('tahun-anggaran.simpan');
        Route::post('/tahun-anggaran/ganti', 'ganti')->name('tahun-anggaran.ganti');
    });
});
