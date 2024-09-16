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
        
        $assetPath = asset(config('thumbnail.asset'));
        $totalVideos = $user->totalVideos();
        $usedVideos = $user->loadCount('videos')->videos_count;
        $videos = $user->videos()->with('thumbnail')
            ->limit(10)
            ->get()
            ->map(function ($v) use($assetPath) {
                $v->thumbnail_url = $v->thumbnail ?  "{$assetPath}/{$v->thumbnail->path}" : null;
                return $v;
            })
            ->toArray();

        return Inertia::render('Dashboard', compact('usedVideos', 'totalVideos', 'videos'));
    }

}
