<?php

namespace App\Http\Controllers;

use App\Models\Set;
use Illuminate\Http\Request;

class SealedController extends Controller
{
    public function __invoke(Set $set)
    {
        return view('sealed', compact('set'));
    }
}
