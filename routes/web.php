<?php

use App\Http\Controllers\{
    PatientController,
    AuthController,
    DashboardController,
    ConsultationController,
    OrdonnanceController,
    UserController
};
use App\Http\Controllers\Admin\{
    ServiceController,
    AdminController,
    MedecinServiceController
};
use Illuminate\Support\Facades\Route;

// 1. Routes publiques
Route::get('/', function () { return view('welcome'); })->name('welcome');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// 2. Routes protégées
Route::middleware(['auth'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- GESTION ADMINISTRATIVE ---
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::resource('services', ServiceController::class);
        Route::post('services/{service}/assign-medecin', [ServiceController::class, 'assignMedecin'])->name('services.assign-medecin');
        Route::resource('users', UserController::class); // Nettoyé : suppression du doublon
        
        // Routes pour la gestion des assignations médecins-services
        Route::get('medecins-services', [MedecinServiceController::class, 'index'])->name('medecins-services.index');
        Route::get('medecins-services/non-assignes', [MedecinServiceController::class, 'medecinsNonAssignes'])->name('medecins-services.non-assignes');
        Route::get('medecins-services/search', [MedecinServiceController::class, 'search'])->name('medecins-services.search');
        Route::get('medecins-services/{service}', [MedecinServiceController::class, 'show'])->name('medecins-services.show');
        Route::post('medecins-services/assign', [MedecinServiceController::class, 'assignMedecinBulk'])->name('medecins-services.assign');
        Route::post('medecins-services/{service}/assign', [MedecinServiceController::class, 'assignMedecin'])->name('medecins-services.assign-service');
        Route::delete('medecins-services/{service}/{medecin}', [MedecinServiceController::class, 'removeMedecin'])->name('medecins-services.remove');
        
        });

    // --- GESTION PATIENTS ---
    Route::resource('patients', PatientController::class);

    // --- GESTION CONSULTATIONS ---
    Route::get('consultations', [ConsultationController::class, 'index'])->name('consultations.index');
    Route::get('patients/{patient}/consultations/create', [ConsultationController::class, 'create'])->name('consultations.create');
    Route::post('patients/{patient}/consultations', [ConsultationController::class, 'store'])->name('consultations.store');
    Route::get('/consultations/{id}/pdf', [ConsultationController::class, 'generatePDF'])->name('consultations.pdf');

    // --- GESTION ORDONNANCES ---
    Route::resource('ordonnances', OrdonnanceController::class);
    Route::get('ordonnances/{id}/pdf', [OrdonnanceController::class, 'generatePDF'])->name('ordonnances.pdf');
});
