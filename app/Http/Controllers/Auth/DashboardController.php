<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $logs = auth()->user()->poolLogs()->latest()->with('set')->paginate();

        return view('auth.dashboard', compact('logs'));
    }
}
