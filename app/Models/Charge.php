<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Charge extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payment_id',
        'payment_intent',
        'charge',
        'balance_transaction',
        'payment_method',
        'type',
        'detail',
        'amount',
        'amount_captured',
        'currency',
        'status',
    ];

    public function user(): BelongsTo 
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    public static function fromStripe($data): Charge
    {
        $payment = Payment::where('payment_intent', $data->payment_intent)->first();

        $card = $data->payment_method_details->card ?? null;
        $detail = [
            'customer' => $data->customer,
            'brand' => $card->brand ?? null,
            'country' => $card->country ?? null,
            'exp_month' => $card->exp_month ?? null,
            'exp_year' => $card->exp_year ?? null,
            'last4' => $card->last4 ?? null,
            'network' => $card->network ?? null,
        ];

        $model = self::create([
            'user_id' => $payment->user_id ?? null,
            'payment_id' => $payment->id ?? null,
            'payment_intent' => $data->payment_intent,
            'charge' => $data->id,
            'balance_transaction' => $data->balance_transaction,
            'payment_method' => $data->payment_method,
            'type' => $data->payment_method_details->type ?? null,
            'detail' => json_encode($detail),
            'amount' => $data->amount,
            'amount_captured' => $data->amount_captured,
            'currency' => $data->currency,
            'status' => $data->status,
        ]);

        if ($payment && $data->status=='succeeded') {
            $payment->status = PaymentStatus::Succeeded->value;
            $payment->save();
        }

        return $model;
    }

}
