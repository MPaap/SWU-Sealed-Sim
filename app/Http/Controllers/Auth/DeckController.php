<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeckController extends Controller
{
    public function __invoke()
    {
        $decks = auth()->user()->decks()
            ->latest()
            ->with(['set', 'leaderCardVersion', 'baseCardVersion'])
            ->paginate();

//        dd($decks->first()->leaderCardVersion);

        return view('auth.deck.index', compact('decks'));
    }
}
