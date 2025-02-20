<?php

use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('app');
});

Route::get('/email/verify/{token}', [AuthController::class, 'verifyEmail'])->name('verification.verify');

Route::post('password/email', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::post('password/reset', [PasswordResetController::class, 'reset']);




Route::get('{any}', function () {
    return view('app');
})->where('any', '.*');