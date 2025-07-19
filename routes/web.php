<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\UserApplicationController;
use App\Http\Controllers\ProfileSelectionController;
use App\Http\Controllers\PropertyContactController;
use App\Http\Controllers\PropertyController;
use App\Http\Middleware\EnsureUserCanAccessAdvertiserPanel;
use App\Livewire\PropertiesIndex;
use App\Livewire\ShowProperties;
use App\Livewire\FavoritePropertiesIndex;
use App\Livewire\NotificationsIndex; // <--- ¡Añade esta línea!
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::prefix('auth/google')->group(function () {
    Route::get('/login', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
    Route::get('/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');
});

Route::view('/about', 'pages.about')->name('about');

Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/propiedades/{slug}', [PropertyController::class, 'show'])->name('properties.show');
Route::post('/propiedades/{property}/contactar', [App\Http\Controllers\PropertyMessageController::class, 'store'])->name('properties.contact');

Route::get('/propiedades', PropertiesIndex::class)->name('properties.index');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/solicitud-perfil', [ProfileSelectionController::class, 'showSelectionForm'])->name('solicitud.perfil');
    Route::get('/solicitud/estado', [UserApplicationController::class, 'status'])->name('solicitud.estado');
    Route::get('/solicitud/{type?}', [UserApplicationController::class, 'create'])->name('solicitud.formulario');

    // Ruta para las propiedades favoritas
    Route::get('/mis-favoritos', FavoritePropertiesIndex::class)->name('user.favorites.index');

    // ¡NUEVO! Ruta para las notificaciones
    Route::get('/mis-notificaciones', NotificationsIndex::class)->name('user.notifications.index'); // <--- ¡NUEVO!

    // Route::middleware([EnsureUserCanAccessAdvertiserPanel::class])->group(function () {
    //     Route::get('/dashboard', function () {
    //         return view('dashboard');
    //     })->name('dashboard');
    // });
});
