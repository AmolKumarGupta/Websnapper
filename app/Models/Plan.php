<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "data",
        "created_at",
        "updated_at",
    ];

    public static function collect(): Collection
    {
        return static::get(['id', 'data'])
            ->map(function ($plan) {
                $data = json_decode($plan->data, true);

                if (! $data) {
                    return [];
                }
                return [...$data, "id" => $plan->id];
            });
    }

    /**
     * @return array plans 
     * 
     * use json rather than using model,
     * for getting plans only
     */
    public static function json(): array 
    {
        return static::collect()->toArray();
    }

    /**
     * @return array plans with user-selected plan
     */
    public static function jsonWithAction(User|Authenticatable $user): array 
    {
        $payment = Payment::where('user_id', $user->id)
            ->where('status', PaymentStatus::Succeeded->value)
            ->latest()
            ->select('plan_id')
            ->first();

        if ($payment) {
            $selected = $payment->plan_id;
        }else {
            $basicPlan = Plan::where('name', 'basic')->first();
            $selected = $basicPlan->id;
        }

        return static::collect()
            ->map(fn ($plan) => [
                ...$plan, 
                "selected" => intval($selected==$plan['id']), 
                "hide" => intval($selected > $plan['id']) 
            ])
            ->toArray();
    }

    /**
     * @return price for the stripe in INR Paise
     */
    public function calculatedPrice(): int
    {
        $data = json_decode($this->data, true);

        return $data['price'] * 80 * 100;
    }

}
