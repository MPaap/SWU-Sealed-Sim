<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $set = \App\Models\Set::where('code', 'SEC')->firstOrFail();

    return (new \App\Helpers\Pack($set)->generate());
});

Route::get('/random', function () {
    return \App\Models\Card::with(['arenas', 'aspects', 'versions', 'keywords', 'traits'])->inRandomOrder()->first();
});
