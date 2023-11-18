<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Video;
use App\Actions\StoreVideo;
use Carbon\Carbon;
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
            $video = Video::with(['user:id,name'])->findOrFail($request->get('id'));

        }else {
            $videoHash = $video;
            $video = Video::with(['user:id,name'])->findOrFail(hashget($videoHash, true));
        }

        $this->authorize($video);

        $video->formattedCreatedAt = Carbon::parse($video->created_at)->format("d M, Y");
        return Inertia::render('Video', compact('videoHash', 'video'));
    }

    function play(Request $request, string $video) {
        if ($request->get('id')) {
            $videoHash = base64_encode($request->get('id'));
            $video = Video::findOrFail($request->get('id'));

        }else {
            $videoHash = $video;
            $video = Video::findOrFail(hashget($videoHash, true));
        }

        $this->authorize('view', $video);

        if (! $video->path) {
            return abort(404);
        }

        $stream = new \App\Actions\VideoStream(storage_path("app/{$video->path}"));
        $stream->start();
    }

    function update(Request $request, $video) 
    {
        // 
    }

    function destroy(Request $request, $video) 
    {
        // 
    }

    function changeTitle(Request $request) 
    {
        $video = Video::findOrFail($request->videoId);
        $this->authorize('view', $video);

        if ($request->title && $video->title != $request->title) {
            $video->title = $request->title;
            $video->save();
        }
    }

    function giveAccess(Request $request) 
    {
        $video = Video::findOrFail($request->videoId);
        $this->authorize('view', $video);

        $request->validate([
            "userEmail" => 'required|email',
        ]);

        
    }

}
