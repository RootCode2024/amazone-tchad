<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('app');
});


Route::get('/email/verify/{token}', [AuthController::class, 'verifyEmail'])->name('verification.verify');

Route::get('{any}', function () {
    return view('app');
})->where('any', '.*');