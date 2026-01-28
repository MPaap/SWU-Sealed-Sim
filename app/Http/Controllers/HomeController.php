<?php

namespace App\Http\Controllers;

use App\Models\Deck;
use App\Models\PoolLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __invoke()
    {
        $sets = \App\Models\Set::orderByDesc('id')->get();

        $generated = [
            'all'   => \App\Models\PoolLog::count(),
            'recent' => \App\Models\PoolLog::where('created_at', '>=', today()->subWeek())->count(),
        ];

        return view('home', compact('sets', 'generated'));
    }
}
