<?php

use App\Http\Controllers\Order\OrderController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth')->group(function(){
    Route::get('/{order}/download', [OrderController::class, 'download'])->can('download', 'order')->name('download');
});
