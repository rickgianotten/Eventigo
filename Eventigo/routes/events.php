<?php

use App\Http\Controllers\Event\EventController;
use App\Models\Event;
use Illuminate\Support\Facades\Route;

Route::get('/', [EventController::class, 'index'])->name('index');

Route::can('create', Event::class)->group(function(){
    Route::get('/create', [EventController::class, 'create'])->name('create');
    Route::post('/create', [EventController::class, 'store'])->name('store');

    Route::get('/create/preview', [EventController::class, 'showPreview'])->name('create.showPreview');
    Route::post('/create/preview', [EventController::class, 'storePreview'])->name('create.storePreview');
});

Route::get('/{event:slug}', [EventController::class, 'show'])->name('show');