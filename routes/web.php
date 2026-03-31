<?php

use App\Http\Controllers\PatientController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\OrdonnanceController;
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);


Route::middleware(['auth'])->group(function () {


    Route::resource('patients', PatientController::class);

    // Ajoute ici TOUS tes futurs modules
    // Route::resource('consultations', ConsultationController::class);
    // Route::resource('ordonnances', OrdonnanceController::class);

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
Route::get('patients/{patient}/consultations/create', [ConsultationController::class, 'create'])
->name('consultations.create');
Route::post('patients/{patient}/consultations', [ConsultationController::class, 'store'])
->name('consultations.store');
Route::get('/consultations/{id}/pdf', [ConsultationController::class, 'generatePDF'])
->name('consultations.pdf');

Route::get('/ordonnances/create/{patient_id}', [OrdonnanceController::class, 'create'])->name('ordonnances.create');
Route::post('/ordonnances/store', [OrdonnanceController::class, 'store'])->name('ordonnances.store');
Route::get('/ordonnances/pdf/{id}', [OrdonnanceController::class, 'generatePDF'])->name('ordonnances.pdf');
