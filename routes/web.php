<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    ClientController,
    ProduitController,
    PoliceController,
    SinistreController,
    PaiementController,
    ComesaController,
    UserController,
    SettingController,
    NotificationTemplateController,
    BackupController,
    RoleController,
    PermissionController
};
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// Redirection de la page d'accueil
Route::redirect('/', '/login');

// Authentification
Auth::routes(['verify' => true]);

// Surcharge de la route de connexion
Route::post('/login', 'App\Http\Controllers\Auth\CustomLoginController@login')
    ->middleware(['throttle:5,1'])
    ->name('login');

// Routes de vérification d'email
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('dashboard');
    })->middleware('signed')->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    })->middleware('throttle:6,1')->name('verification.send');
});

// Routes protégées
Route::middleware(['auth', 'verified'])->group(function () {
    // Tableau de bord
    Route::get('/home', [HomeController::class, 'index'])->name('dashboard');

    // Ressources communes
    Route::resources([
        'clients' => ClientController::class,
        'produits' => ProduitController::class,
        'polices' => PoliceController::class,
        'sinistres' => SinistreController::class,
        'paiements' => PaiementController::class,
    ]);

    // COMESA
    Route::prefix('comesa')->name('comesa.')->group(function () {
        Route::get('/', [ComesaController::class, 'index'])->name('index');
        // Routes pour la configuration COMESA (stockage local)
        Route::get('/config', [ComesaController::class, 'getConfig'])->name('config.get');
        Route::post('/config', [ComesaController::class, 'saveConfig'])->name('config.save');
        // Routes pour les taux de change locaux
        Route::get('/rates', [ComesaController::class, 'getRates'])->name('rates.get');
        Route::post('/rates', [ComesaController::class, 'saveRates'])->name('rates.save');
        // Routes for regional rules
        Route::get('/rules', [ComesaController::class, 'getRules'])->name('rules.get');
        Route::post('/rules', [ComesaController::class, 'saveRules'])->name('rules.save');
    });

    // Administration
    Route::prefix('admin')->name('admin.')->middleware('role:super_admin|admin')->group(function () {
        // Gestion des utilisateurs
        Route::resource('users', UserController::class)->except(['show']);
        
        // Rôles et permissions
        Route::resource('roles', RoleController::class)->except(['show']);
        Route::resource('permissions', PermissionController::class)->except(['show']);
        
        // Paramètres
        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
        
        // Sauvegardes
        Route::get('backups', [BackupController::class, 'index'])->name('backups.index');
        Route::post('backups/create', [BackupController::class, 'create'])->name('backups.create');
        Route::delete('backups/{backup}', [BackupController::class, 'destroy'])->name('backups.destroy');
    });

    // Routes spécifiques aux rôles
    Route::middleware('role:agent')->group(function () {
        // Routes pour les agents d'assurance
    });

    Route::middleware('role:expert')->group(function () {
        // Routes pour les experts sinistres
    });

    Route::middleware('role:comptable')->group(function () {
        // Routes pour les comptables
    });
});

// Routes API
Route::prefix('api')->middleware('auth:sanctum')->group(function () {
    // Vos routes API ici
});

// Gestion des erreurs 404
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});