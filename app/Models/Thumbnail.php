<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Thumbnail extends Model
{
    use HasFactory;

    public static function boot() 
    {
        parent::boot();

        self::deleting(function ($thumbnail) {

            if ($thumbnail->path) {
                Storage::delete( config("thumbnail.relative_path") ."/". $thumbnail->path);
            }

        });
    }

}
