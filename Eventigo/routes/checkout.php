<?php

use App\Http\Controllers\Checkout\CheckoutController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function(){
    Route::post('/', [CheckoutController::class, 'store'])->name('store');

    Route::get('/succes', [CheckoutController::class, 'succes'])->name('succes');
    Route::get('/cancel', [CheckoutController::class, 'cancel'])->name('cancel');
});