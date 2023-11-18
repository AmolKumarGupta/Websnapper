<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoAccess extends Model
{
    use HasFactory;

    protected $fillable = [
        "video_id",
        "model_type",
        "model_id",
        "email",
    ];

    public static function give(Video $video, string|Model $data) 
    {
        $model_type = null;
        $model_id = null;
        $email = null;

        if ($data instanceof Model) {
            $model_type = get_class($data);
            $model_id = $data->id;

        }else if (is_string($data)) {
            $user = User::where('email', $data)->first();
            if ($user) {
                return static::give($video, $user);
            }
            $email = $data;

        }else {
            throw new Exception('$data is not the instance of Model::class nor an email');
        }

        return static::firstOrCreate([
            "video_id" => $video->id,
            "model_type" => $model_type,
            "model_id" => $model_id,
            "email" => $email,
        ]);
    }

}
