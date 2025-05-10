<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\Client\DashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Client\InstallationController;
use App\Http\Controllers\Client\RealtimeDataController;
use App\Http\Controllers\Client\InverterController;
use App\Http\Controllers\DimensionnementController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\OnduleurController;
use App\Http\Controllers\OnduleurConfigController;
use App\Http\Controllers\DevisController;
use App\Http\Controllers\LogActiviteController;
use App\Http\Controllers\TechnicianController;
use App\Http\Controllers\SuiviProductionController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\MeteoController;
use App\Http\Controllers\RegionalPerformanceController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Admin\FunctionalityController;
use App\Http\Controllers\Admin\FormationController as AdminFormationController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\FormationController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\OptimisationController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\ServiceController;

// Routes publiques
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/service', function () {
    return view('service');
})->name('service');

// Routes pour les formations
Route::prefix('formation')->group(function () {
    Route::get('/', [FormationController::class, 'index'])->name('formation');
    Route::get('/inscription', [FormationController::class, 'show'])->name('inscription');
    Route::post('/inscription', [FormationController::class, 'inscription'])->middleware(['auth'])->name('formation.inscription');
    Route::get('/{formation}/flyer', [FormationController::class, 'downloadFlyer'])->name('formation.flyer.download');
});

// Routes d'administration
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Route du tableau de bord admin
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])
        ->name('dashboard');
    
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::put('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('markAllAsRead');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::delete('/', [NotificationController::class, 'destroyAll'])->name('destroyAll');
    });

    // Routes pour le monitoring des services IA
    Route::get('/services/status', [App\Http\Controllers\Admin\ServiceStatusController::class, 'index'])
        ->name('services.status');
    Route::post('/services/reset-fallback', [App\Http\Controllers\Admin\ServiceStatusController::class, 'resetFallbackMode'])
        ->name('services.reset-fallback');
    Route::post('/services/reset-quota', [App\Http\Controllers\Admin\ServiceStatusController::class, 'resetQuotaCount'])
        ->name('services.reset-quota');

    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);    Route::resource('functionalities', FunctionalityController::class);    Route::resource('devis', \App\Http\Controllers\Admin\DevisController::class);
    Route::get('/devis/download-pdf/{id}', [\App\Http\Controllers\Admin\DevisController::class, 'downloadPdf'])->name('devis.download-pdf');    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);
    Route::put('/orders/{order}/update-status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])
        ->name('orders.update-status');  // Le nom de la route inclut déjà le préfixe 'admin.' du groupe
    
    Route::get('formations/inscriptions', [AdminFormationController::class, 'inscriptions'])->name('formations.inscriptions');
    Route::delete('formations/inscriptions/{inscription}', [AdminFormationController::class, 'destroyInscription'])->name('formations.inscriptions.destroy');
    Route::get('formations/{formation}/flyer', [AdminFormationController::class, 'downloadFlyer'])->name('formations.flyer.download');
    
    Route::resource('formations', AdminFormationController::class);
    Route::resource('installations', InstallationController::class);
    Route::get('installations/pending', [InstallationController::class, 'pending'])->name('installations.pending');
    Route::resource('products', ProductController::class);
});

// Routes protégées par authentification
Route::middleware(['auth'])->group(function () {
    // Route du tableau de bord client
    Route::get('/dashboard', [App\Http\Controllers\Client\DashboardController::class, 'index'])
        ->middleware(['client'])
        ->name('dashboard');

    Route::get('/performances-regionales', [RegionalPerformanceController::class, 'index'])->name('performances.regionales');
    Route::get('/api/performances-regionales/data', [RegionalPerformanceController::class, 'getData'])->name('performances.regionales.data');

    // Routes pour onduleurs
    Route::get('/onduleurs', [OnduleurController::class, 'index'])->name('onduleurs.index');
    Route::get('/onduleurs/create', [OnduleurController::class, 'create'])->name('onduleurs.create');
    Route::post('/onduleurs', [OnduleurController::class, 'store'])->name('onduleurs.store');
    Route::get('/onduleurs/{onduleur}', [OnduleurController::class, 'show'])->name('onduleurs.show');
    Route::get('/onduleurs/{onduleur}/edit', [OnduleurController::class, 'edit'])->name('onduleurs.edit');
    Route::put('/onduleurs/{onduleur}', [OnduleurController::class, 'update'])->name('onduleurs.update');
    Route::delete('/onduleurs/{onduleur}', [OnduleurController::class, 'destroy'])->name('onduleurs.destroy');
    Route::post('/onduleurs/{onduleur}/toggle-connection', [OnduleurController::class, 'toggleConnection'])->name('onduleurs.toggle-connection');
    Route::get('/onduleurs/{onduleur}/performance', [OnduleurController::class, 'performance'])->name('onduleurs.performance');
    Route::post('/onduleurs/test-connection', [OnduleurController::class, 'testConnection'])->name('onduleurs.test-connection');

    // Routes pour le profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Routes pour les activités    Route::get('/activites', [LogActiviteController::class, 'index'])->name('activites.index');    
    
    // Routes pour la maintenance
    Route::prefix('maintenance')->group(function () {
        Route::get('/predictive', [MaintenanceController::class, 'index'])->name('maintenance-predictive');
        Route::post('/', [MaintenanceController::class, 'store'])->name('maintenance.store');
        Route::get('/{id}/edit', [MaintenanceController::class, 'edit'])->name('maintenance.edit');
        Route::put('/{id}', [MaintenanceController::class, 'update'])->name('maintenance.update');
    });

    // Routes pour la configuration des onduleurs
    Route::get('/onduleur/config', [OnduleurConfigController::class, 'show'])->name('onduleur.config');
    Route::post('/onduleur/config', [OnduleurConfigController::class, 'save'])->name('onduleur.config.save');

    // Routes pour les devis
    Route::get('/devis/creer', [DevisController::class, 'create'])->name('devis.create');
    Route::post('/devis', [DevisController::class, 'store'])->name('devis.store');
    Route::get('/devis/{devis}/resultats', [DevisController::class, 'resultats'])->name('devis.resultats');
    Route::get('/devis', [DevisController::class, 'index'])->name('devis.index');
    Route::get('/devis/{devis}/download-pdf', [DevisController::class, 'downloadPdf'])->name('devis.download-pdf');
    
    // Routes pour les paramètres
    Route::get('/parametres', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    Route::put('/parametres/update', [App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');
    Route::put('/parametres/security', [App\Http\Controllers\SettingsController::class, 'security'])->name('settings.security');
    Route::put('/parametres/display', [App\Http\Controllers\SettingsController::class, 'display'])->name('settings.display');
    Route::post('/parametres/2fa', [App\Http\Controllers\SettingsController::class, 'toggleTwoFactor'])->name('settings.2fa');

    // Routes pour les dimensionnements
    Route::get('/dimensionnements', [DimensionnementController::class, 'index'])->name('dimensionnements.index');
    Route::get('/dimensionnements/create', [DimensionnementController::class, 'create'])->name('dimensionnements.create');
    Route::post('/dimensionnements', [DimensionnementController::class, 'store'])->name('dimensionnements.store');
    Route::get('/dimensionnements/{dimensionnement}', [DimensionnementController::class, 'show'])->name('dimensionnements.show');
});

// Routes des services
Route::middleware(['auth'])->group(function () {
    Route::get('/suivi-production', [SuiviProductionController::class, 'index'])->name('suivi-production');
    Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('maintenance');
    Route::get('/optimisation', [OptimisationController::class, 'index'])->name('optimisation');
    Route::get('/support', [SupportController::class, 'index'])->name('support');
});

// Routes pour le blog
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/blog/search', [BlogController::class, 'search'])->name('blog.search');
Route::get('/blog/category/{slug}', [BlogController::class, 'category'])->name('blog.category');
Route::get('/blog/tag/{slug}', [BlogController::class, 'tag'])->name('blog.tag');
Route::get('/blog/author/{id}', [BlogController::class, 'author'])->name('blog.author');

// Routes pour les pages statiques
Route::get('/fonctionnalite', function () {
    return view('fonctionnalite');
})->name('fonctionnalite');

Route::get('/market-place', [MarketplaceController::class, 'index'])->name('market-place');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/installation', function () {
    return view('installation');
})->name('installation');

// Routes pour le contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Routes pour les paiements
Route::get('/checkout/{product}', [PaymentController::class, 'showCheckout'])->name('checkout');
Route::post('/process-payment', [PaymentController::class, 'processPayment'])->name('process.payment');
Route::get('/payment-success/{order}', [PaymentController::class, 'paymentSuccess'])->name('payment.success');

// Routes pour les commandes
Route::middleware(['auth'])->group(function () {
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/success', [OrderController::class, 'success'])->name('orders.success');
});

// Routes pour les techniciens
Route::middleware(['auth', 'role:technician'])->prefix('technician')->name('technician.')->group(function () {
    Route::get('installations', [TechnicianController::class, 'installations'])->name('installations');
    Route::get('maintenance', [TechnicianController::class, 'maintenance'])->name('maintenance');
});

Route::get('/technician/form', [TechnicianController::class, 'showForm'])->name('technician.form');
Route::post('/technician/form', [TechnicianController::class, 'submitForm'])->name('technician.form.submit');
Route::post('/technician/submit', [TechnicianController::class, 'submit'])->name('technician.submit');

// Routes pour le suivi de production
Route::get('/suivi-production', [SuiviProductionController::class, 'index'])->name('suivi-production');
Route::get('/suivi-production/data', [SuiviProductionController::class, 'getData'])->name('suivi-production.data');
Route::get('/suivi-production/export-pdf', [SuiviProductionController::class, 'exportPDF'])->name('suivi-production.export-pdf');
Route::get('/suivi-production/export-csv', [SuiviProductionController::class, 'exportCSV'])->name('suivi-production.export-csv');

Route::middleware(['auth', 'role:technicien'])->name('technicien.')->prefix('technicien')->group(function () {
    Route::get('/onduleurs', [App\Http\Controllers\Technicien\OnduleurController::class, 'index'])->name('onduleurs.index');
    Route::get('/onduleurs/{onduleur}', [App\Http\Controllers\Technicien\OnduleurController::class, 'show'])->name('onduleurs.show');
    Route::get('/onduleurs/{onduleur}/edit', [App\Http\Controllers\Technicien\OnduleurController::class, 'edit'])->name('onduleurs.edit');
    Route::post('/onduleurs/{onduleur}/check-connection', [App\Http\Controllers\Technicien\OnduleurController::class, 'checkConnection'])->name('onduleurs.check-connection');
    Route::post('/onduleurs/{onduleur}/test-connection', [App\Http\Controllers\Technicien\OnduleurController::class, 'testConnection'])->name('onduleurs.test-connection');
    Route::post('/onduleurs/{onduleur}/reset-connection', [App\Http\Controllers\Technicien\OnduleurController::class, 'resetConnection'])->name('onduleurs.reset-connection');
    Route::delete('/onduleurs/{onduleur}', [App\Http\Controllers\Technicien\OnduleurController::class, 'destroy'])->name('onduleurs.destroy');
});

// Routes pour les services
Route::prefix('services')->group(function () {
    Route::get('/', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/{service}', [ServiceController::class, 'show'])->name('services.show');
    Route::post('/{service}/request', [ServiceController::class, 'submitRequest'])
        ->middleware(['auth'])
        ->name('services.request.submit');
});

// Routes pour les demandes de services client
Route::middleware(['auth'])->group(function() {
    Route::get('/mes-demandes-services', [App\Http\Controllers\Client\DemandeServiceController::class, 'index'])
        ->name('client.demandes-services.index');
});

// Routes admin pour les services
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {    Route::get('/services', [ServiceController::class, 'adminIndex'])->name('services.index');
    Route::get('/services/create', [ServiceController::class, 'adminCreate'])->name('services.create');
    Route::post('/services', [ServiceController::class, 'adminStore'])->name('services.store');
    Route::get('/services/{service}/edit', [ServiceController::class, 'adminEdit'])->name('services.edit');
    Route::put('/services/{service}', [ServiceController::class, 'adminUpdate'])->name('services.update');
    Route::delete('/services/{service}', [ServiceController::class, 'adminDestroy'])->name('services.destroy');
    Route::get('/service-requests', [ServiceController::class, 'adminRequests'])->name('services.requests');
    Route::put('/service-requests/{serviceRequest}/status', [ServiceController::class, 'updateRequestStatus'])
        ->name('services.requests.status');
});

Route::get('/ia-services', function () {
    return view('ia-services');
})->name('ia-services');

require __DIR__.'/auth.php';
