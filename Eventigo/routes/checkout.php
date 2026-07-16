<?php

use App\Http\Controllers\Checkout\CheckoutController;
use App\Http\Middleware\Checkout\EnsureCheckoutCompleted;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function(){
    Route::post('/', [CheckoutController::class, 'store'])->name('store');

    Route::get('/succes', [CheckoutController::class, 'succes'])->middleware(EnsureCheckoutCompleted::class)->name('succes');
    Route::get('/cancel', [CheckoutController::class, 'cancel'])->middleware(EnsureCheckoutCompleted::class)->name('cancel');
});