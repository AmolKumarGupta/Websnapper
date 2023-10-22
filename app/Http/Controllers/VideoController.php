<?php

namespace App\Http\Controllers;

use App\Actions\StoreVideo;
use App\Models\Video;
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

    function show(Request $request, string $video) 
    {
        if ($request->get('id')) {
            $videoHash = base64_encode($request->get('id'));
            $video = Video::findOrFail($request->get('id'));

        }else {
            $videoHash = $video;
            $video = Video::findOrFail(base64_decode($videoHash));
        }

        $this->authorize($video);
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
