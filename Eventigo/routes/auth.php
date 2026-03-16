<?php

use App\Http\Controllers\Auth\HostRegisterController;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\Auth\UserRegisterController;
use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function(){
    Route::get('/register/host', [HostRegisterController::class, 'create'])->name('host.create');
    Route::post('/register/host', [HostRegisterController::class, 'store'])->name('host.store');

    Route::get('/register', [UserRegisterController::class, 'create'])->name('register.create');
    Route::post('/register', [UserRegisterController::class, 'store'])->name('register.store');

    Route::get('/login', [SessionController::class, 'create'])->name('login.create');
    Route::post('/login', [SessionController::class, 'store'])->middleware('throttle:login')->name('login.store');
});


Route::delete('/logout', [SessionController::class, 'destroy'])->middleware('auth')->name('logout');