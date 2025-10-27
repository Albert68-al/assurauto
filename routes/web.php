<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    ProduitController,
    PaymentController,
    ComesaController,
    UserController,
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

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// Redirection de la page d'accueil
Route::redirect('/', '/login');

// Authentification
Auth::routes(['verify' => true]);

// Surcharge de la route de connexion
Route::post('/login', 'App\Http\Controllers\Auth\CustomLoginController@login')
    ->middleware(['throttle:5,1'])
    ->name('custom.login');

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
        // 'paiements' => PaymentController::class,
    ]);

//     // Routes pour les paiements
// Route::prefix('payments')->name('payments.')->group(function () {
//     // Liste des paiements
//     Route::get('/', [PaymentController::class, 'index'])->name('index');
    
//     // Création de paiement
//     Route::get('/create', [PaymentController::class, 'create'])->name('create');
//     Route::post('/', [PaymentController::class, 'store'])->name('store');
    
//     // Visualisation et édition
//     Route::get('/{payment}', [PaymentController::class, 'show'])->name('show');
//     Route::get('/{payment}/edit', [PaymentController::class, 'edit'])->name('edit');
//     Route::put('/{payment}', [PaymentController::class, 'update'])->name('update');
//     Route::delete('/{payment}', [PaymentController::class, 'destroy'])->name('destroy');
    
//     // Exportation
//     Route::get('/export/excel', [PaymentController::class, 'exportExcel'])->name('export.excel');
//     Route::get('/export/pdf', [PaymentController::class, 'exportPdf'])->name('export.pdf');
//     Route::get('/{payment}/invoice', [PaymentController::class, 'showInvoice'])->name('invoice');

// // Export wallets (named route used by views)
// Route::get('/export/wallets', [PaymentController::class, 'exportExcel'])->name('export.wallets');    
//     // Gestion du statut
//     Route::patch('/{payment}/status', [PaymentController::class, 'updateStatus'])->name('update-status');
    
    // Gestion des portefeuilles
    Route::prefix('wallet')->name('wallet.')->group(function () {
        Route::get('/', [PaymentController::class, 'walletIndex'])->name('index');
        Route::get('/{user}', [PaymentController::class, 'walletShow'])->name('show');
        Route::post('/', [PaymentController::class, 'walletAddFunds'])->name('store');
        Route::post('/adjust', [PaymentController::class, 'walletAdjustBalance'])->name('adjust');
        Route::get('/export/excel', [PaymentController::class, 'exportWalletsExcel'])->name('export.excel');
    });
});

    Route::prefix('client')->name('client.')->group(function () {
        Route::get('/clientdashboard', [DashboardController::class, 'index'])->name('dashboard.index');
        Route::resource('vehicules', VehiculeController::class);
        Route::resource('/polices', PoliceController::class);
        Route::get('/profiles', [ProfileController::class, 'index'])->name('profile.index');
        Route::get('/wallets', [WalletController::class, 'index'])->name('wallet.index');
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
    // Modèles de notifications
    Route::resource('notification-templates', NotificationTemplateController::class);   

// Gestion des erreurs 404
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});