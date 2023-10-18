<?php

namespace App\Models;

use App\Enums\VideoStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        "fk_user_id",
        "title",
        "path",
        "status",
    ];

    protected $casts = [
        'status' => VideoStatus::class,
    ];
    
}
