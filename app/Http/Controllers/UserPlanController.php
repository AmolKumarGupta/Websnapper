<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserPlanController extends Controller
{

    public function plans(Request $request) 
    {
        $plans = Plan::jsonWithAction(auth()->user());
        // dd($plans);

        return Inertia::render('UpgradePlan', compact('plans'));
    }

}
