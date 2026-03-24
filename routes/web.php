<?php

use App\Http\Controllers\PatientController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

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
