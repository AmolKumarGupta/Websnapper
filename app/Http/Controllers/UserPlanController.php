<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class UserPlanController extends Controller
{

    public function plans(Request $request) 
    {
        return Inertia::render('UpgradePlan', []);
    }

}
