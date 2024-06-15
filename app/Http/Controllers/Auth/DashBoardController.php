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
        
        $totalVideos = $user->totalVideos();
        $usedVideos = $user->loadCount('videos')->videos_count;
        $videos = $user->videos()->with('thumbnail')
            ->limit(10)
            ->get()
            ->map(function ($v) {
                $v->hash = hashget($v->id);
                $v->thumbnail_url = $v->thumbnail ? asset("storage/thumbnails/{$v->thumbnail->path}") : null;
                return $v;
            })
            ->toArray();

        return Inertia::render('Dashboard', compact('usedVideos', 'totalVideos', 'videos'));
    }

}
