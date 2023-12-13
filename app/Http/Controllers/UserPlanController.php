<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserPlanController extends Controller
{

    public function plans(Request $request) 
    {
        $order = array_flip(['videos', 'backup', 'support']);
        
        $plans = collect(Plan::jsonWithAction(auth()->user()) )
            ->map(function($p) use($order) {
                uksort($p['buffs'], function ($a, $b) use($order) {
                    return $order[$a] - $order[$b];
                });

                return $p;
            })
            ->toArray();

        return Inertia::render('UpgradePlan', compact('plans'));
    }

}
