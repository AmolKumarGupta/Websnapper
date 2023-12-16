<?php 

return [

    "secret" => env('STRIPE_SECRET_KEY'),

    "pkey" => env('STRIPE_PUBLISHABLE_KEY'),
    
    "webhook" => env('STRIPE_WEBHOOK_KEY'),
];