<?php

use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\ConsultationController;
use App\Http\Controllers\Api\OrdonnanceController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Authentification API
Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);

// Routes protégées par authentification
Route::middleware('auth:sanctum')->group(function () {
    
    // Routes pour les patients
    Route::apiResource('patients', PatientController::class);
    
    // Routes pour les consultations
    Route::get('patients/{patient}/consultations', [ConsultationController::class, 'index']);
    Route::post('patients/{patient}/consultations', [ConsultationController::class, 'store']);
    Route::apiResource('consultations', ConsultationController::class)->except(['index', 'store']);
    
    // Routes pour les ordonnances
    Route::get('patients/{patient}/ordonnances', [OrdonnanceController::class, 'index']);
    Route::post('patients/{patient}/ordonnances', [OrdonnanceController::class, 'store']);
    Route::apiResource('ordonnances', OrdonnanceController::class)->except(['index', 'store']);
    
    // Routes pour les services (admin seulement)
    Route::middleware('role:administrateur')->group(function () {
        Route::apiResource('services', ServiceController::class);
        Route::post('services/{service}/assign-medecin', [ServiceController::class, 'assignMedecin']);
        Route::delete('services/{service}/remove-medecin/{medecin}', [ServiceController::class, 'removeMedecin']);
    });
    
    // Routes pour les utilisateurs (admin seulement)
    Route::middleware('role:administrateur')->group(function () {
        Route::apiResource('users', UserController::class);
    });
    
    // Routes spécifiques pour les médecins
    Route::middleware('role:medecin')->group(function () {
        Route::get('mes-patients', [PatientController::class, 'myPatients']);
        Route::get('mon-service/patients', [PatientController::class, 'servicePatients']);
    });
    
    // Routes pour les patients (accès à leurs propres données)
    Route::middleware('role:patient')->group(function () {
        Route::get('mon-dossier', [PatientController::class, 'myDossier']);
        Route::get('mes-consultations', [ConsultationController::class, 'myConsultations']);
        Route::get('mes-ordonnances', [OrdonnanceController::class, 'myOrdonnances']);
    });
    
    // Route de déconnexion
    Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);
    
    // Route pour obtenir l'utilisateur connecté
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
