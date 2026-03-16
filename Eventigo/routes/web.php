<?php

use App\Http\Controllers\Landingpage\LandingPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', LandingPageController::class)->name('home');
