<?php

use App\Http\Controllers\Checkout\CheckoutController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CheckoutController::class, 'create'])->name('create');