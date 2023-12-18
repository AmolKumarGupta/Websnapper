<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\PaymentStatus;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class, 'fk_user_id', 'id');
    }

    public function totalVideos(): int 
    {
        $payment = Payment::where('user_id', $this->id)
            ->where('status', PaymentStatus::Succeeded->value)
            ->select('plan_id')
            ->first();

        if ($payment == null) {
            $plan = Plan::where('name', 'basic')->select('data->buffs->videos as videos')->first();
            return $plan->videos ?? 1;
        }
        
        $plan = Plan::where('id', $payment->plan_id)->select('data->buffs->videos as videos')->first();
        return $plan->videos ?? 1;
    }

}
