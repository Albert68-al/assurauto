<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    ProduitController,
    PaiementController,
    ComesaController,
    SettingController,
    NotificationTemplateController,
    BackupController,
    RoleController,
    PermissionController
};
use App\Http\Controllers\Client\DashboardController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Client\PoliceController;
use App\Http\Controllers\Client\WalletController;
use App\Http\Controllers\Client\SinistreController;
use App\Http\Controllers\Client\VehiculeController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\AssuranceController;
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
        'produits' => ProduitController::class,
        'paiements' => PaiementController::class,
    ]);

    Route::prefix('client')->name('client.')->group(function () {
        Route::get('/clientdashboard', [DashboardController::class, 'index'])->name('dashboard.index');
        Route::resource('vehicules', VehiculeController::class);
        Route::resource('/polices', PoliceController::class);
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/my-wallet', [WalletController::class, 'transactions'])->name('wallet-transaction');
        Route::get('/sinistres', [SinistreController::class, 'index'])->name('sinistre.index');
    });

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
        // Endpoint to add a new country locally (no external API)
        Route::post('/pays', [ComesaController::class, 'storePays'])->name('pays.store');
    });

    // Administration
    Route::prefix('admin')->name('admin.')->middleware('role:super_admin|admin')->group(function () {
        // Gestion des utilisateurs
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        
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
        
        // Journalisation des activités
        Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::delete('activity-logs/{activityLog}', [ActivityLogController::class, 'destroy'])->name('activity-logs.destroy');
        Route::post('activity-logs/clear', [ActivityLogController::class, 'clear'])->name('activity-logs.clear');
    });

    Route::prefix('admin')->group(function () {
        Route::get('/wallets', [AccountController::class, 'index'])->name('wallets.index');
        Route::post('/wallets', [AccountController::class, 'store'])->name('wallets.store');
        Route::get('/wallets/deposit', [AccountController::class, 'depositForm'])->name('wallets.deposit.form');
        Route::post('/wallets/deposit', [AccountController::class, 'deposit'])->name('wallets.deposit');
    });

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/polices', [AssuranceController::class, 'index'])->name('polices.index');
        Route::post('/polices/{id}/approve', [AssuranceController::class, 'approve'])->name('polices.approve');
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