<?php

use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarLocationController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\FlightHotelController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\ManagerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::get('/sanctum/csrf-cookie', function (Request $request) {
    return response()->json(['message' => 'CSRF Cookie set']);
});

Route::prefix('managers')->group(function () {
    Route::get('/', [ManagerController::class, 'index']);
    Route::get('/{id}', [ManagerController::class, 'show']);
    Route::post('/', [ManagerController::class, 'store']);
    Route::put('/', [ManagerController::class, 'update']);
    Route::put('/password', [ManagerController::class, 'updatePassword']);
    Route::delete('/{id}', [ManagerController::class, 'destroy']);
});

Route::get('/dashboard', [ManagerController::class, 'dashboard']);

Route::prefix('customers')->group(function () {
    Route::get('/', [CustomerController::class, 'index']);
    Route::get('/{id}', [CustomerController::class, 'show']);
    Route::delete('/{id}', [CustomerController::class, 'destroy']);
});

// Routes pour les pays
Route::prefix('cities')->group(function () {
    Route::get('/', [CityController::class, 'index']);
});

// Routes pour les réservations de vols
Route::prefix('flights')->group(function () {
    Route::get('/', [FlightController::class, 'index']);
    Route::post('/', [FlightController::class, 'store']);
    Route::put('/{id}', [FlightController::class, 'update']);
    Route::delete('/{id}', [FlightController::class, 'destroy']);
    
    Route::put('/{id}/status', [FlightController::class, 'updateStatus']);
});

// Routes pour les réservations d’hôtels
Route::prefix('hotels')->group(function () {
    Route::get('/', [HotelController::class, 'index']);
    Route::post('/', [HotelController::class, 'store']);
    Route::put('/{id}', [HotelController::class, 'update']);
    Route::delete('/{id}', [HotelController::class, 'destroy']);

    Route::put('/{id}/status', [HotelController::class, 'updateStatus']);
});

// Routes pour les réservations Vol + Hôtel
Route::prefix('flight-hotel')->group(function () {
    Route::get('/', [FlightHotelController::class, 'index']);
    Route::post('/', [FlightHotelController::class, 'store']);
    Route::put('/{id}', [FlightHotelController::class, 'update']);
    Route::delete('/{id}', [FlightHotelController::class, 'destroy']);
    
    Route::put('/{id}/status', [FlightHotelController::class, 'updateStatus']);
});

// Routes pour la location de voitures
Route::prefix('car-locations')->group(function () {
    Route::get('/', [CarLocationController::class, 'index']);
    Route::post('/', [CarLocationController::class, 'store']);
    Route::put('/{id}', [CarLocationController::class, 'update']);
    Route::delete('/{id}', [CarLocationController::class, 'destroy']);
    
    Route::put('/{id}/status', [CarLocationController::class, 'updateStatus']);
});


// Routes pour les details de la reservation
Route::prefix('bookings')->group(function () {
    Route::get('/hotel/{id}', [HotelController::class, 'show']);
    Route::get('/flight/{id}', [FlightController::class, 'show']);
    Route::get('/flight-hotel/{id}', [FlightHotelController::class, 'show']);  
    Route::get('/car-location/{id}', [CarLocationController::class, 'show']);
});

// Routes publiques (sans authentification)
Route::post('login', [AuthController::class, 'login'])->name('auth.login');
Route::post('register', [AuthController::class, 'register'])->name('auth.register');


Route::get('password/reset/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');



// Routes protégées avec Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::post('refresh', [AuthController::class, 'refresh'])->name('auth.refresh');
    Route::get('user', [AuthController::class, 'user'])->name('auth.user');
    Route::post('/email/resend', [AuthController::class, 'resendVerificationEmail']);
});

