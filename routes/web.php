<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('sealed');
});

Route::get('pool/{set}', function ($set) {
    $set = \App\Models\Set::where('code', $set)->firstOrFail();

    return [
        (new \App\Helpers\Pack($set)->generate()),
        (new \App\Helpers\Pack($set)->generate()),
        (new \App\Helpers\Pack($set)->generate()),
        (new \App\Helpers\Pack($set)->generate()),
        (new \App\Helpers\Pack($set)->generate()),
        (new \App\Helpers\Pack($set)->generate()),
    ];
});

Route::get('/random', function () {
    return \App\Models\Card::with(['arenas', 'aspects', 'versions', 'keywords', 'traits'])->inRandomOrder()->first();
});
