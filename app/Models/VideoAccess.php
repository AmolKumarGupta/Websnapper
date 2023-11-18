<?php

namespace App\Models;

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

}
