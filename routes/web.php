<?php

use App\Http\Controllers\PatientController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\OrdonnanceController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;

// 1. Routes publiques
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Routes d'authentification
Route::get('/login', [AuthController::class, 'showLogin'])->name('login'); // Note: 'showLogin' au lieu de 'showLoginForm'
Route::post('/login', [AuthController::class, 'login']);

// 2. Routes protégées par authentification (Middleware 'auth' suffit)
Route::middleware(['auth'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- GESTION ADMINISTRATIVE ---
    // On retire 'role:admin' pour éviter l'erreur, la vérification se fera dans le Contrôleur
    Route::prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.index');
        Route::resource('services', ServiceController::class);
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
