<?php

namespace App\Models;

use App\Enums\VideoStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

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

    public static function boot() 
    {
        parent::boot();

        self::deleting(function ($video) {
            Storage::delete($video->path);
            $video->thumbnail?->delete();
            VideoView::where('video_id', $video->id)->delete();
            VideoAccess::where('video_id', $video->id)->delete();
            ServiceVideo::where('video_id', $video->id)->delete();
        });
    }

    public function user(): BelongsTo 
    {
        return $this->belongsTo(User::class, "fk_user_id", "id");
    }

    public function views(): int
    {
        return VideoView::where('video_id', $this->id)->count();
    }

    public function thumbnail(): HasOne
    {
        return $this->hasOne(Thumbnail::class);
    }

    /**
     * @return string absolute path of the video
     */
    public function getPath(): string 
    {
        return storage_path("app/{$this->path}");
    }
    
    /**
     * check if video is already uploaded on the service or not
     */
    public function isSynced(): bool 
    {
        return ServiceVideo::where('video_id', $this->id)->first() != null;
    }

    public function getSharableLink(): string 
    {
        $model = ServiceVideo::where('video_id', $this->id)->first();
        if (! $model) {
            return "";
        }

        $payload = json_decode($model->payload, true);
        return $payload["link"] ?? "";
    }

}
