<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Folder extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $visible = [
        "id",
        "parent_id",
        "parent",
        "hash",
        "name",
        "date",
    ];

    public static function boot() 
    {
        parent::boot();

        self::creating(function ($model) {
            $model->hash = $model->getUniqHash();
        });

        self::deleting(function ($model) {
            /** @todo expensive operation */
            $model->videos->each(fn($video) => $video->delete());
        });
    }

    protected function date(): Attribute
    {
        return new Attribute(
            get: fn () => $this->created_at->format('d M Y'),
        );
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function parent(): HasOne {
        return $this->hasOne(Folder::class, 'id', 'parent_id');
    }

    public function videos(): HasMany {
        return $this->hasMany(Video::class);
    }

    public static function findBySlug(string $slug) {
        $components = explode("~", $slug);
        $index = array_key_last($components);
        $hash = $components[$index];

        return Folder::where('hash', $hash)->first();
    }

    /**
     * generate unique hash under three attempts
     */
    protected function getUniqHash() {
        $attempts = 0;
        $exists = function ($hash) use ($attempts) {
            if ($attempts > 3) {
                throw new \Exception(
                    'Folder::getUniqHash does not have enough '.
                    'entropy and failed URL generation. This method should generate a very random ID.'
                );
            }

            return Folder::where('hash', $hash)->exists();
        };

        
        do {
            $hash = bin2hex(openssl_random_pseudo_bytes(3));
            $attempts++;

        }while($exists($hash));

        return $hash;
    }

}
