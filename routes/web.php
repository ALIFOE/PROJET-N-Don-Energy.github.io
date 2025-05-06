<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\Client\DashboardController;
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
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;

// Routes publiques
Route::get('/', function () {
    return view('home');
})->name('home');

// Routes protégées par authentification
Route::middleware(['auth'])->group(function () {
    Route::get('/systeme-intelligent', 'App\Http\Controllers\SystemeIntelligentController@index')
        ->name('systeme-intelligent');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Routes pour les performances régionales
    Route::get('/performances-regionales', [RegionalPerformanceController::class, 'index'])->name('performances.regionales');
    Route::get('/api/performances-regionales/data', [RegionalPerformanceController::class, 'getData'])->name('performances.regionales.data');

    // ...autres routes existantes...

    // Routes pour les dimensionnements
    Route::resource('dimensionnements', DimensionnementController::class);

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

    // Routes pour le client
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Routes pour les activités
    Route::get('/activites', [LogActiviteController::class, 'index'])->name('activites.index');
    Route::get('/activites/export-pdf', [LogActiviteController::class, 'exportPDF'])->name('activites.export-pdf');
    Route::delete('/activites/reset', [LogActiviteController::class, 'reset'])->name('activites.reset');

    // Routes pour la maintenance prédictive
    Route::get('/maintenance-predictive', [App\Http\Controllers\MaintenanceController::class, 'index'])->name('maintenance-predictive');
    Route::post('/maintenance', [App\Http\Controllers\MaintenanceController::class, 'store'])->name('maintenance.store');
    Route::get('/maintenance/{id}/edit', [App\Http\Controllers\MaintenanceController::class, 'edit'])->name('maintenance.edit');
    Route::put('/maintenance/{id}', [App\Http\Controllers\MaintenanceController::class, 'update'])->name('maintenance.update');
    Route::delete('/maintenance/{id}', [App\Http\Controllers\MaintenanceController::class, 'destroy'])->name('maintenance.destroy');
    
    // Routes pour les prévisions météo
    Route::get('/previsions-meteo', [App\Http\Controllers\MeteoController::class, 'index'])->name('previsions-meteo');
    Route::get('/meteo/donnees-actuelles', [App\Http\Controllers\MeteoController::class, 'getDonneesActuelles'])->name('meteo.donnees-actuelles');
    Route::get('/meteo/alertes/configuration', [App\Http\Controllers\MeteoController::class, 'showAlerteConfig'])->name('meteo.alertes.config');
    Route::post('/meteo/alertes/configuration', [App\Http\Controllers\MeteoController::class, 'saveAlerteConfig'])->name('meteo.alertes.save');

    // Routes pour les rapports et analyses
    Route::get('/rapports-analyses', [App\Http\Controllers\RapportController::class, 'index'])->name('rapports-analyses');

    // Routes pour la configuration des onduleurs
    Route::get('/onduleur/config', [OnduleurConfigController::class, 'show'])->name('onduleur.config');
    Route::post('/onduleur/config', [OnduleurConfigController::class, 'save'])->name('onduleur.config.save');

    // Routes pour les devis
    Route::get('/devis/creer', [DevisController::class, 'create'])->name('devis.create');
    Route::post('/devis', [DevisController::class, 'store'])->name('devis.store');
    Route::get('/devis/{devis}/resultats', [DevisController::class, 'resultats'])->name('devis.resultats');
    Route::get('/devis', [DevisController::class, 'index'])->name('devis.index');
    Route::get('/devis/{devis}/download-pdf', [DevisController::class, 'downloadPdf'])->name('devis.download-pdf');
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

// Routes pour les formations
Route::get('/formation', [App\Http\Controllers\FormationController::class, 'index'])->name('formation');
Route::get('/formation/inscription', [App\Http\Controllers\FormationController::class, 'show'])->name('formation.inscription.page');
Route::post('/formation/inscription', [App\Http\Controllers\FormationController::class, 'inscription'])->name('formation.inscription');

Route::get('/installation', function () {
    return view('installation');
})->name('installation');

// Routes pour le contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/service', function () {
    return view('service');
})->middleware(['auth'])->name('service');

// Routes pour l'administration des messages de contact
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/contacts', [ContactController::class, 'admin'])->name('admin.contacts');
    Route::patch('/admin/contacts/{id}/mark-read', [ContactController::class, 'markAsRead'])->name('admin.contacts.mark-read');
    Route::delete('/admin/contacts/{id}', [ContactController::class, 'destroy'])->name('admin.contacts.destroy');
});

Route::get('/dimensionnement', [DimensionnementController::class, 'showForm'])->name('dimensionnement');
Route::post('/dimensionnement', [DimensionnementController::class, 'submit'])->name('dimensionnement.submit');

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

// Routes d'administration
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::get('/users/{user}', [App\Http\Controllers\Admin\UtilisateurController::class, 'show'])->name('users.show');
});

Route::get('/technician/form', [TechnicianController::class, 'showForm'])->name('technician.form');
Route::post('/technician/form', [TechnicianController::class, 'submitForm'])->name('technician.form.submit');
Route::post('/technician/submit', [TechnicianController::class, 'submit'])->name('technician.submit');

Route::post('/formation/inscription', [App\Http\Controllers\FormationController::class, 'inscription'])->name('formation.inscription');

Route::get('/inscription', [App\Http\Controllers\FormationController::class, 'show'])->name('inscription');

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

Route::resource('messages', MessageController::class);

require __DIR__.'/auth.php';
