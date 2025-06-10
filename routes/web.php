<?php

use App\Http\Controllers\Auth\GoogleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Rutas de autenticación con Google
Route::prefix('auth/google')->group(function () {
    Route::get('/login', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
    Route::get('/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');
});

// Rutas protegidas
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});