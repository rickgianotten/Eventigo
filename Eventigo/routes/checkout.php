<?php

use App\Http\Controllers\Checkout\CheckoutController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function(){
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('store');
});