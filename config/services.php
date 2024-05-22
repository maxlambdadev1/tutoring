<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    
    'sendgrid' => [
        'key' => env('SENDGRID_API_KEY'),
    ],

    'googlemap' => [
        'key' => env('GOOGLE_MAPS_GEOCODING_API_KEY'),
    ],

    'podium' => [
        'url' => 'https://api.podium.com/api/v2/conversations',
        'key' => env('PODIUM_TOKEN'),
        'locationUidFirst' => env('PODIUM_LOCATION_ID_FIRST', '61eb0b61-518c-518d-bf74-234c77455914'),
        'locationUidSecond' => env('PODIUM_LOCATION_ID_SECOND', '766e6c6a-9617-5d46-a613-3c2fc0146928'),
    ],

];
