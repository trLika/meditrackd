<?php

use App\Http\Controllers\{
    PatientController,
    AuthController,
    DashboardController,
    ConsultationController,
    OrdonnanceController,
    UserController
};
use App\Http\Controllers\Auth\DoctorRegisterController;
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
Route::get('/register-doctor', [DoctorRegisterController::class, 'showRegistrationForm'])->name('register.doctor.form');
Route::post('/register-doctor', [DoctorRegisterController::class, 'register'])->name('register.doctor');

// Routes pour la réinitialisation du mot de passe
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Réinitialisation via question de sécurité
Route::get('/reset-security', [AuthController::class, 'showSecurityQuestionForm'])->name('password.security.step1');
Route::match(['get', 'post'], '/reset-security/verify', [AuthController::class, 'verifyEmailForSecurity'])->name('password.security.step2');
Route::post('/reset-security/reset', [AuthController::class, 'resetWithSecurityAnswer'])->name('password.security.reset');

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
        
        // --- RAPPORTS ET STATISTIQUES ---
        Route::get('reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/export', [\App\Http\Controllers\ReportController::class, 'exportPDF'])->name('reports.export');
        
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
