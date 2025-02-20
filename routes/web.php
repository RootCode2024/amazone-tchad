<?php

use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('app');
});

Route::get('/email/verify/{token}', [AuthController::class, 'verifyEmail'])->name('verification.verify');
// Route::get('password/reset/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
// Route::post('password/reset', [PasswordResetController::class, 'reset']);





Route::get('{any}', function () {
    return view('app');
})->where('any', '.*');