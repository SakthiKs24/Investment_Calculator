<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvestmentCalculatorController;

Route::get('/', [InvestmentCalculatorController::class, 'index']);
Route::post('/calculate', [InvestmentCalculatorController::class, 'calculate']);

