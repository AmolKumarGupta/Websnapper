<?php

namespace App\Models;

use App\Enums\VideoStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        "fk_user_id",
        "title",
        "path",
        "status",
    ];

    protected $hidden = [
        "path",
    ];

    protected $casts = [
        'status' => VideoStatus::class,
    ];

    public function user(): BelongsTo 
    {
        return $this->belongsTo(User::class, "fk_user_id", "id");
    }
    
}
