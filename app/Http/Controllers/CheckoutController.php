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
            
            $customer = $stripe->customers->create();

            $paymentIntent = $stripe->paymentIntents->create([
                'customer' => $customer->id,
                'setup_future_usage' => 'off_session',
                'amount' => 100,
                'currency' => 'inr',
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
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                info($paymentIntent);
            
            default:
                echo 'Received unknown event type ' . $event->type;
        }

        http_response_code(200);
    }

}
