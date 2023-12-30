<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use App\Models\Video;
use App\Models\VideoView;
use App\Utility\Stat;
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
                        return '$ ' . $query
                            ->where('status', PaymentStatus::Succeeded->value)
                            ->sum('amount') / 100;
                    }),
            ),
        ]);
    }
}