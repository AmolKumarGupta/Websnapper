<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Folder extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $visible = [
        "id",
        "parent_id",
        "name",
        "date",
    ];

    public static function boot() 
    {
        parent::boot();

        self::creating(function ($model) {
            $model->hash = bin2hex(openssl_random_pseudo_bytes(3));
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

    public function videos(): HasMany {
        return $this->hasMany(Video::class);
    }

}
