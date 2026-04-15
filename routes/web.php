<?php

use App\Http\Controllers\PatientController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\OrdonnanceController;
use Illuminate\Support\Facades\Route;

// 1. Routes publiques (accessibles à tous)
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// 2. Routes protégées par authentification
Route::middleware(['auth'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- GESTION PATIENTS : Accès complet (Admin + Médecin + Stagiaire) ---
    // Note : Si tu veux restreindre certaines actions (suppression),
    // on utilisera des permissions plus tard.
    Route::middleware(['role:admin|medecin|stagiaire'])->group(function () {
        Route::resource('patients', PatientController::class);
    });

    // --- GESTION CONSULTATIONS : Accès pour tous ---
    Route::middleware(['role:admin|medecin|stagiaire'])->group(function () {
        Route::get('consultations', [ConsultationController::class, 'index'])->name('consultations.index');
        Route::get('patients/{patient}/consultations/create', [ConsultationController::class, 'create'])->name('consultations.create');
        Route::post('patients/{patient}/consultations', [ConsultationController::class, 'store'])->name('consultations.store');
        Route::get('/consultations/{id}/pdf', [ConsultationController::class, 'generatePDF'])->name('consultations.pdf');
    });

    // --- GESTION ORDONNANCES : Réservé aux Médecins et Admins ---
    Route::middleware(['role:admin|medecin'])->group(function () {
        Route::resource('Ordonnances', OrdonnanceController::class);
        Route::get('Ordonnances/{id}/pdf', [OrdonnanceController::class, 'generatePDF'])->name('Ordonnances.pdf');
    });
});
