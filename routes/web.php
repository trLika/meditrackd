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



    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
Route::middleware(['auth'])->group(function () {
    Route::get('consultations', [ConsultationController::class, 'index'])
    ->name('consultations.index');
    Route::get('patients/{patient}/consultations/create', [ConsultationController::class, 'create'])
    ->name('consultations.create');
    Route::post('patients/{patient}/consultations', [ConsultationController::class, 'store'])
    ->name('consultations.store');
    Route::get('/consultations/{id}/pdf', [ConsultationController::class, 'generatePDF'])
    ->name('consultations.pdf');
});


//les routes pour les actions concernant les ordonnances
Route::get('ordonnances/{id}/pdf', [OrdonnanceController::class, 'generatePDF'])
->name('ordonnances.pdf');
Route::resource('ordonnances', OrdonnanceController::class);
