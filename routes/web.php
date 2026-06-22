<?php

use App\Http\Controllers\MedicationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MedicationController::class, 'index'])->name('home');
Route::get('/medications/{medication:slug}', [MedicationController::class, 'show'])->name('medications.show');
