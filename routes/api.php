<?php

use App\Http\Controllers\Api\AkunApiController;
use Illuminate\Support\Facades\Route;

Route::get('/test/akun', [AkunApiController::class, 'index']);