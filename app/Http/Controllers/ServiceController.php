<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Services\Contract\ServiceManager;
use App\Services\Drive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ServiceController extends Controller
{

    public function sync(Request $request, ServiceManager $manager)
    {
        $request->validate(["videoId" => "required"]);

        /** @var Video */
        $video = Video::findOrFail($request->videoId);
        if ($video == null || !$this->authorize('edit', $video)) {
            return Redirect::back();
        }

        if ($video->isSynced()) {
            return Redirect::back();
        }
        
        $service = $manager->get('google-drive', $video->fk_user_id);
        $service->save($video);
    }
}

