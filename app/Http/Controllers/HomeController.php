<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke()
    {
        $sets = \App\Models\Set::orderByDesc('id')->get();

        return view('home', compact('sets'));
    }
}
