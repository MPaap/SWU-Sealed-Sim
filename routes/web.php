<?php

use Illuminate\Support\Facades\Route;

Route::get('/', \App\Http\Controllers\HomeController::class)->name('home');

Route::get('sealed/{set:code}', \App\Http\Controllers\SealedController::class)->name('sealed');

Route::get('pool/{set:code}', \App\Http\Controllers\PoolController::class)->name('pool');

Route::get('auth/google', [\App\Http\Controllers\Auth\GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [\App\Http\Controllers\Auth\GoogleController::class, 'handleGoogleCallback']);

Route::middleware('auth')->group(function () {
    //
});
