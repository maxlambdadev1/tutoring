<?php

namespace App\Trait;

use Stripe\Stripe;
use Stripe\StripeClient;

trait TutoringStripe
{
    public function getStripeInstance()
    {        
        return new StripeClient([
            "api_key" => env('STRIPE_API_KEY'),
            "api_version" => env('STRIPE_API_VERSION'),
        ]);
    }

    public function createStripeAccount($details)
    {
        $stripeId = null;
        return $stripeId;
    }

    public function createStripeCustomer($details)
    {
        $stripeId = null;
        return $stripeId;
    }
}
