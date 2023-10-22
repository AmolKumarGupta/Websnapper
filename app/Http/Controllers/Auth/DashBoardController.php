<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\VideoResource;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashBoardController extends Controller
{
    function index(Request $request) 
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        
        $totalVideos = 25;
        $usedVideos = $user->videos()->count();
        $videos = $user->videos()
            ->limit(10)
            ->get()
            ->map(function ($v) {
                $v->hash = hashget($v->id);
                return $v;
            })
            ->toArray();

        return Inertia::render('Dashboard', compact('usedVideos', 'totalVideos', 'videos'));
    }

}
