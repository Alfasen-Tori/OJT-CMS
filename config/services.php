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

    'mailjet' => [
        'key' => env('868d0508bed92eb32cd6b3f517529432'),
        'secret' => env('ae474e24576a6b5fe4a6dfdc5babe698'),
        'transactional' => [
            'call' => true,
            'options' => [
                'url' => 'api.mailjet.com',
                'version' => 'v3.1',
                'call' => true,
                'secured' => true,
            ],
        ],
        'common' => [
            'call' => true,
            'options' => [
                'url' => 'api.mailjet.com',
                'version' => 'v3',
                'call' => true,
                'secured' => true,
            ],
        ],
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    

];
