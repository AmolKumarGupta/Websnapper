<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Charge;
use App\Models\Payment;
use App\Models\User;
use App\Models\Video;
use App\Models\VideoView;
use App\Utility\Stat;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller 
{
    public function index(Request $request) 
    {
        return Inertia('Admin/Index', [
            'stats' => Stat::collect(
                new Stat(User::class),
                new Stat(Video::class),
                new Stat(VideoView::class),
                (new Stat(Payment::class))
                    ->label('Amount Earned')
                    ->agg(function ($query) {
                        return 'Rs ' . $query
                            ->where('status', PaymentStatus::Succeeded->value)
                            ->sum('amount') / 100;
                    }),
            ),
            'transactions' => Charge::with('user')->latest()
                ->limit(5)->get()
                ->map(function ($charge) {
                    $detail = json_decode($charge->detail, true);

                    return [
                        "name" => ucfirst($charge->user->name),
                        "amount" => "Rs " . $charge->amount / 100,
                        "date" => Carbon::parse($charge->created_at)->diffForHumans(),
                        "status" => ($charge->status==PaymentStatus::Succeeded->value) ? "paid" : "unpaid",
                        "account" => $detail['brand'] ?? "",
                        "accountNumber" => $detail['last4'] ?? "",
                        "expiry" => ($detail['exp_month'] ?? "") ."/". ($detail['exp_year'] ?? ""),
                    ];
                })
                ->toArray()
        ]);
    }
}
