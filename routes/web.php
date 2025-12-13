<?php

use App\Http\Controllers\PatientController as P;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [P::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%Route des Patients %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
Route::middleware(['auth'])->group(function () {
    //%%%%%%%%%%%%%%%%%%%%%%%%%%%%Route des Patients %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
    Route::resource('patients', P::class);
    //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%


    Route::get('/patients/{patient}/scan/new', [ScanController::class, 'create'])->name('scans.create');
    // Route pour envoyer l'image : /patients/{id}/scan

    Route::post('/patients/{patient}/scan', [ScanController::class, 'store'])->name('scans.store');
    Route::delete('/scans/{scan}', [ScanController::class, 'destroy'])->name('scans.destroy');
    Route::get('/scans/{scan}', [ScanController::class, 'show'])->name('scans.show');

    // Route pour que le médecin enregistre SON diagnostic final (Validation)
    Route::put('/scans/{scan}', [ScanController::class, 'update'])->name('scans.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
