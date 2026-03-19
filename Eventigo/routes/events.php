<?php

use App\Http\Controllers\Event\EventController;
use App\Models\Event;
use Illuminate\Support\Facades\Route;

Route::get('/', [EventController::class, 'index'])->name('index');

Route::get('/create', [EventController::class, 'create'])->can('create', Event::class)->name('create');

Route::get('/{event:slug}', [EventController::class, 'show'])->name('show');
