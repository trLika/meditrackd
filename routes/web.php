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
    AdminController
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
        Route::resource('users', UserController::class); // Nettoyé : suppression du doublon
    

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
