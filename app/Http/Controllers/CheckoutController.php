<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    public function index(Request $request) 
    {
        return Inertia::render('Checkout', [
            "csrf_token" => csrf_token(),
            "pkey" => config('stripe.pkey'),
        ]);
    }

    public function stripeCreate(Request $request) 
    {
        try {
            $stripe = new \Stripe\StripeClient(config('stripe.secret'));
            
            $paymentIntent = $stripe->paymentIntents->create([
                'amount' => 10,
                'currency' => 'usd',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);
            
            $output = [
                'clientSecret' => $paymentIntent->client_secret,
            ];
            
            return response()->json($output);
            
        }catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
