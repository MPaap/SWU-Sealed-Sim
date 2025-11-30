<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $set = \App\Models\Set::where('code', 'SEC')->firstOrFail();

    $packs = [
        (new \App\Helpers\Pack($set)->generate()),
//        (new \App\Helpers\Pack($set)->generate()),
//        (new \App\Helpers\Pack($set)->generate()),
//        (new \App\Helpers\Pack($set)->generate()),
//        (new \App\Helpers\Pack($set)->generate()),
//        (new \App\Helpers\Pack($set)->generate()),
    ];

    return view('sealed', compact('packs'));
});

Route::get('/random', function () {
    return \App\Models\Card::with(['arenas', 'aspects', 'versions', 'keywords', 'traits'])->inRandomOrder()->first();
});
