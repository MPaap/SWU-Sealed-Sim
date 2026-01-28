<?php

use Illuminate\Support\Facades\Route;

Route::get('/', \App\Http\Controllers\HomeController::class)->name('home');

Route::get('sealed/{set:code}', \App\Http\Controllers\SealedController::class)->name('sealed');

Route::get('pool/{set:code}', \App\Http\Controllers\PoolController::class)->name('pool');
Route::post('pool/{set:code}/{seed}', [\App\Http\Controllers\PoolController::class, 'save'])->name('pool.save');

Route::get('login', \App\Http\Controllers\Auth\LoginController::class)->name('login');
Route::get('auth/google', [\App\Http\Controllers\Auth\GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [\App\Http\Controllers\Auth\GoogleController::class, 'handleGoogleCallback']);

Route::middleware('auth')->group(function () {
    Route::get('me/pools', \App\Http\Controllers\Auth\PoolController::class)->name('auth.pool');
    Route::get('me/decks', \App\Http\Controllers\Auth\DeckController::class)->name('auth.deck');
});

Route::get('deck/{deck}', [\App\Http\Controllers\DeckController::class, 'show'])->name('deck.show');
Route::get('deck/{deck}/json', [\App\Http\Controllers\DeckController::class, 'json'])->name('deck.json');

Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
