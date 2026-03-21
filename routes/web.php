<?php
use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;
Route::get('/', function () {
    return view('welcome');
});
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
