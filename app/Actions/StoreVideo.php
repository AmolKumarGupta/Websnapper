<?php 

namespace App\Actions;

use Exception;
use FFMpeg\FFMpeg;
use App\Models\User;
use App\Models\Video;
use App\Models\Thumbnail;
use App\Enums\VideoStatus;
use Illuminate\Support\Str;
use FFMpeg\Coordinate\TimeCode;
use Illuminate\Support\Facades\Storage;

class StoreVideo 
{

    public static function handle(User $user, $file): bool|Exception 
    {
        $path = "videos/{$user->id}";

        $isUploaded = Storage::put($path, $file);

        if ($isUploaded === false) {
            throw new Exception("File not saved");
        }

        $video = Video::create([
            "fk_user_id" => $user->id,
            "title" => now()->format("d M Y, H:i:s"),
            "path" => $path ."/". $file->hashName(),
            "status" => VideoStatus::Active,
        ]);

        $thumbnailName = Str::uuid() . ".jpeg";

        $storagePath = config('thumbnail.path');
        if ( !file_exists( $storagePath ) ) {
            mkdir( $storagePath );
        }

        /** @var FFMpeg $ffmpeg */
        $ffmpeg = resolve(FFMpeg::class);
        $ffmpeg->open(storage_path("app/{$video->path}"))
            ->frame(TimeCode::fromSeconds(3))
            ->save( "{$storagePath}/{$thumbnailName}" );

        $thumbnail = new Thumbnail;
        $thumbnail->video_id = $video->id;
        $thumbnail->path = $thumbnailName;
        $thumbnail->save();
        
        return true;
    }

}