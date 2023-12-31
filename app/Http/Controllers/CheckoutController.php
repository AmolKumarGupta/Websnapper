<?php

namespace App\Http\Controllers;

use App\Models\Charge;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\StripeCustomer;
use Exception;
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
        $user = auth()->user();
        $plan = Plan::find($request->plan_id);
        if (! $plan) {
            return abort(403);
        }
        $price = $plan->calculatedPrice();

        try {
            $stripe = new \Stripe\StripeClient(config('stripe.secret'));
            
            $cusRecord = StripeCustomer::where('user_id', $user->id)->first();
            if (! $cusRecord) {
                $customer = $stripe->customers->create([
                    'name' => $user->name,
                    'email' => $user->email,
                ]);

                $cusRecord = StripeCustomer::create([
                    'user_id' => $user->id,
                    'cus_id' => $customer->id,
                ]);
            }

            $paymentIntent = $stripe->paymentIntents->create([
                'customer' => $cusRecord->cus_id,
                'setup_future_usage' => 'off_session',
                'amount' => $price,
                'currency' => 'inr',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            Payment::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'customer'=> $paymentIntent->customer,
                'payment_intent' => $paymentIntent->id,
                'amount' => $paymentIntent->amount,
                'currency' => $paymentIntent->currency,
            ]);
            
            $output = [
                'clientSecret' => $paymentIntent->client_secret,
            ];
            
            return response()->json($output);
            
        }catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function webhook(Request $request) 
    {
        $stripe = new \Stripe\StripeClient(config('stripe.secret'));
        $endpoint_secret = config('stripe.webhook');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );

        } catch(\UnexpectedValueException $e) {
            http_response_code(400);
            exit();
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            http_response_code(400);
            exit();
        }

        switch ($event->type) {
            case 'charge.succeeded':
                Charge::fromStripe($event->data->object);
            
            default:
                echo 'Received unknown event type ' . $event->type;
        }

        http_response_code(200);
    }

}
