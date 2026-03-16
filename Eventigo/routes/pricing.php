<?php

use App\Http\Controllers\Pricing\PricingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PricingController::class, 'index'])->name('index');
Route::post('/', [PricingController::class, 'store'])->name('store');