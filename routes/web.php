<?php

use App\Http\Controllers\MedicationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MedicationController::class, 'index'])->name('home');
