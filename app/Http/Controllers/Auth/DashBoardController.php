<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashBoardController extends Controller
{
    function index(Request $request) 
    {
        $totalVideos = 25;
        $usedVideos = 0;

        return Inertia::render('Dashboard', compact('usedVideos', 'totalVideos'));
    }

}
