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
        $totalVideos = fn() => $user->totalVideos();
        $usedVideos = fn() => $user->loadCount('videos')->videos_count;
        $videos = fn() => $user->videos()->with('thumbnail')
            ->limit(10)
            ->get()
            ->map(function ($v) use($assetPath) {
                $v->thumbnail_url = $v->thumbnail ?  "{$assetPath}/{$v->thumbnail->path}" : null;
                return $v;
            })
            ->toArray();

        $folders = fn() => $user->folders()
            ->where('parent_id', null)->get()
            ->append('date');

        return Inertia::render('Dashboard', compact('usedVideos', 'totalVideos', 'videos', 'folders'));
    }

}
