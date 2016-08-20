<?php

return [

    'auth' => [
        'google_client_id' => env('AUTH_GOOGLE_CLIENT_ID', null),
        'google_client_secret' => env('AUTH_GOOGLE_CLIENT_SECRET', null),
        'mahapanel_bypass' => env('AUTH_MAHAPANEL_BYPASS', false),
    ],
    'mail' => [
        'contact_from' => env('MAIL_CONTACT_FROM', 'root'),
        'contact_to' => env('MAIL_CONTACT_TO', null),
        'book_request_from' => env('MAIL_BOOK_REQUEST_FROM', 'root'),
        'book_request_to' => env('MAIL_BOOK_REQUEST_TO', null),
    ],
    'git_versioning' => env('GIT_VERSIONING', false),
    'require_ssl' => env('REQUIRE_SSL', false),
    'require_mahapanel_ssl' => env('REQUIRE_MAHAPANEL_SSL', false),

];
