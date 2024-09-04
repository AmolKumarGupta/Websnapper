<?php

namespace App\Http\Controllers;

use App\Actions\StoreVideo;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

class RecordController extends Controller
{
    
    public function record(Request $request) 
    {
        $request->validate([
            "event" => "required",
            "data" => "required|file",
            "num" => "required",
        ]);

        if ( $request->event == 'finished' ) {
            return $this->handleFinished($request);
        }

        $firstBuffer = false;

        if ($request->uuid == null) {
            $request->merge(['uuid' => Str::uuid()]);
            $firstBuffer = true;
        }

        /** @var User $user */
        $user = auth()->user();
        $file = $request->file('data');

        $path = $file->storeAs(
            "queue/{$request->uuid}", 
            $request->num . "." . $file->extension()
        );

        return response()->json([
            "status" => "processing",
            "data" => [ "uuid" => $request->uuid ],
        ]);
    }

    private function handleFinished(Request $request) {
        if (! $request->uuid) {
            return response()->json(["error" => "uuid is missing"], 500);
        }

        if (! Str::isUuid($request->uuid)) {
            return response()->json(["error" => "invalid uuid format"], 400);
        }

        $relativePath = "queue/{$request->uuid}";
        $path = storage_path("app/{$relativePath}");
        $file = Str::uuid() . ".webm";

        $process = Process::fromShellCommandline("cat {$path}/* > {$path}/{$file}");
        $process->run();

        if (!$process->isSuccessful()) {
            return response()->json(["error" => "process failed"], 500);
        }

        $res = StoreVideo::handle(auth()->user(), new UploadedFile("{$path}/{$file}", $file));

        if ( !$res ) {
            return response()->json(["error" => "unable to store video"], 500);
        }

        Storage::deleteDirectory($relativePath);

        return response()->json(["status" => "created"]);
    }

}
