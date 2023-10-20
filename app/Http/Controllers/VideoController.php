<?php

namespace App\Http\Controllers;

use App\Actions\StoreVideo;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    function index(Request $request) 
    {
        // 
    }

    function store(Request $request): void 
    {
        $request->validate([
            'video' => ['required', 'max:51200'],
        ]);

        StoreVideo::handle(auth()->user(), $request->file('video'));
    }

    function show(Request $request, $video) 
    {
        // 
    }

    function update(Request $request, $video) 
    {
        // 
    }

    function destroy(Request $request, $video) 
    {
        // 
    }

}
