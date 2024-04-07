<?php

namespace App\Models;

use App\Enums\VideoStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function views(): int
    {
        return VideoView::where('video_id', $this->id)->count();
    }

    /**
     * @return string absolute path of the video
     */
    public function getPath(): string 
    {
        return storage_path("app/{$this->path}");
    }
    
}
