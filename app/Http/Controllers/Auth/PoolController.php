<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PoolController extends Controller
{
    public function __invoke()
    {
        $logs = auth()->user()->poolLogs()->latest()->with('set')->paginate();

        return view('auth.pool.index', compact('logs'));
    }
}
