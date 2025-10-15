<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// Page d'accueil
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentification
Auth::routes([
    'verify' => true,
    'login' => 'App\Http\Controllers\Auth\CustomLoginController@showLoginForm',
    'logout' => 'App\Http\Controllers\Auth\LoginController@logout'
]);

// Surcharge de la route de connexion pour utiliser le contrôleur personnalisé
Route::post('/login', 'App\Http\Controllers\Auth\CustomLoginController@login')->name('login');

// Routes protégées
Route::middleware(['auth', 'verified'])->group(function () {
  Route::get('/home', [HomeController::class, 'index'])->name('dashboard');
    
    // Clients
    Route::resource('clients', 'App\Http\Controllers\ClientController');
    
    // Polices
    Route::resource('polices', 'App\Http\Controllers\PoliceController');
    
    // Sinistres
    Route::resource('sinistres', 'App\Http\Controllers\SinistreController');
    
    // Paiements
    Route::resource('paiements', 'App\Http\Controllers\PaiementController');
    
    // COMESA
    Route::prefix('comesa')->group(function () {
        Route::get('/', 'App\Http\Controllers\ComesaController@index')->name('comesa.index');
    });
    
    // Utilisateurs
    Route::middleware(['can:manage users'])->group(function () {
        Route::resource('users', 'App\Http\Controllers\UserController');
    });
    
    // Configuration
    Route::middleware(['can:manage settings'])->group(function () {
        Route::get('/settings', 'App\Http\Controllers\SettingsController@index')->name('settings');
    });
});

// Routes de vérification d'email
Route::get('/email/verify', function () {
    return view('auth.verify');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');