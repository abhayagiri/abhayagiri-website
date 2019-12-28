<?php

return [

    'auth' => [
        'google_client_id' => env('AUTH_GOOGLE_CLIENT_ID', null),
        'google_client_secret' => env('AUTH_GOOGLE_CLIENT_SECRET', null),
        'mahapanel_bypass' => env('AUTH_MAHAPANEL_BYPASS', false),
        'mahapanel_admin' => env('AUTH_MAHAPANEL_ADMIN', 'devmonk@abhayagiri.org'),
    ],
    'git_versioning' => env('GIT_VERSIONING', false),
    'human_timezone' => 'America/Los_Angeles',

    /*
    |--------------------------------------------------------------------------
    | Book Cart
    |--------------------------------------------------------------------------
    */

    'book_cart' => [

        'session_key' => 'books',

    ],

    /*
    |--------------------------------------------------------------------------
    | Long Running Request Time Limit
    |--------------------------------------------------------------------------
    |
    | The time limit for routes that have the
    | App\Http\Middleware\LongRunningRequest middleware. Currently this is used
    | for elfinder routes to compensate for slowdowns due to large numbers of
    | files in directories.
    |
    */

    'long_running_request_time_limit' => 60 * 5, // 5 minutes

    'mail' => [
        'contact_from' => env('MAIL_CONTACT_FROM', 'root'),
        'contact_to' => env('MAIL_CONTACT_TO', null),

        /*
        |--------------------------------------------------------------------------
        | Book Request "To" Address
        |--------------------------------------------------------------------------
        |
        | This is the administrator email that book requests should go to.
        |
        */

        'book_request_to' => [
            'address' => env('MAIL_BOOK_REQUEST_TO_ADDRESS', 'hello@example.com'),
            'name' => env('MAIL_BOOK_REQUEST_TO_NAME', 'Example'),
        ],

    ],

];
