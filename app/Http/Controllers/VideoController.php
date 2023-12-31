<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Video;
use App\Actions\StoreVideo;
use App\Models\User;
use App\Models\VideoAccess;
use App\Models\VideoView;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VideoController extends Controller
{
    function index(Request $request) 
    {
        // 
    }

    function store(Request $request) 
    {
        $request->validate([
            'video' => ['required', 'max:51200'],
        ]);

        /** @var \App\Models\User */
        $user = auth()->user();
        $total = $user->totalVideos();
        $used = $user->videos->count();

        if ($used >= $total) {
            return back()->with('error', 'video limit is reached');
        }

        StoreVideo::handle($user, $request->file('video'));
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

        /** @var \App\Models\User $authUser */
        $authUser = auth()->user();

        $can = ['edit' => $authUser->can('edit', $video)];
        $view_count = $video->views();

        return Inertia::render('Video', compact('can', 'videoHash', 'video', 'view_count'));
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
        $this->authorize('edit', $video);

        if ($request->title && $video->title != $request->title) {
            $video->title = $request->title;
            $video->save();
        }
    }

    function giveAccess(Request $request) 
    {
        $video = Video::findOrFail($request->videoId);
        $this->authorize('edit', $video);

        $request->validate([
            "userEmail" => 'required|email',
        ]);

        VideoAccess::give($video, $request->userEmail);
    }

    function views(Request $request)
    {
        $request->validate([
            "videoId" => 'required',
        ]);

        $video = Video::findOrFail($request->videoId);
        if ($video == null) {
            return $this->index($request);
        }

        $prev = VideoView::where("video_id", $video->id)
            ->where("model_type", User::class)
            ->where("model_id", auth()->id())
            ->latest()
            ->first();

        if (
            $prev 
            && $prev->created_at 
            && now()->diffInMinutes($prev->created_at, true) < 1
        ) {
            return $this->index($request);
        }
            
        VideoView::create([
            "video_id" => $video->id,
            "model_type" => User::class,
            "model_id" => auth()->id(),
        ]);
    }

    public function leftedVideoCount(Request $request) 
    {
        /** @var \App\Models\User */
        $user = auth()->user();
        $total = $user->totalVideos();
        $used = $user->videos->count();

        return response()->json([
            'cnt'  => $total - $used
        ]);
    }

}
