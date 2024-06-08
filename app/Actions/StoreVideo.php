<?php 

namespace App\Actions;

use App\Enums\VideoStatus;
use App\Models\User;
use App\Models\Video;
use Exception;
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

        Video::create([
            "fk_user_id" => $user->id,
            "title" => now()->format("d M Y, H:i:s"),
            "path" => $path ."/". $file->hashName(),
            "status" => VideoStatus::Active,
        ]);
        
        return true;
    }

}