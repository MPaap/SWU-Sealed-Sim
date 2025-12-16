<?php

use Illuminate\Support\Facades\Route;

Route::get('/', \App\Http\Controllers\HomeController::class)->name('home');

Route::get('sealed/{set:code}', \App\Http\Controllers\SealedController::class)->name('sealed');

Route::get('pool/{set:code}', \App\Http\Controllers\PoolController::class)->name('pool');

Route::middleware('auth')->group(function () {
    Route::get('data/pack', [\App\Http\Controllers\DataController::class, 'pack'])->name('data.pack');
    Route::post('data/pack', [\App\Http\Controllers\DataController::class, 'packStore'])->name('data.pack.store');

    Route::get('data/pack/{pack_data}/delete', [\App\Http\Controllers\DataController::class, 'packDelete'])->name('data.pack.delete');
});
