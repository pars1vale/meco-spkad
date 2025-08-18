<?php

use Illuminate\Support\Facades\Route;

/** for side bar menu active */
function set_active($route)
{
    if (is_array($route)) {
        return in_array(Request::path(), $route) ? 'active' : '';
    }
    return Request::path() == $route ? 'active' : '';
}


Route::get('/', [App\Http\Controllers\LandingController::class, 'index'])->name('landing.index');

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('home', function () {
        return view('dashboard.home');
    });
    Route::group(['namespace' => 'App\Http\Controllers'], function () {
        // -------------------------- main dashboard ----------------------//
        Route::controller(HomeController::class)->group(function () {
            Route::get('/home', 'index')->middleware('auth')->name('home');
        });
    });
    require __DIR__ . '/dashboard/referensi.php';
});

require __DIR__ . '/auth/auth.php';
