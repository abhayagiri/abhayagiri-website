<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Export Directory
    |--------------------------------------------------------------------------
    |
    | The directory that contains the exports.
    |
    */

    'path' => public_path('export'),

    /*
    |--------------------------------------------------------------------------
    | Archive Prefix
    |--------------------------------------------------------------------------
    |
    | The prefix that goes in the beginning of the archive.
    |
    */

    'prefix' => 'abhayagiri-public',

    /*
    |--------------------------------------------------------------------------
    | Default Import Database URL
    |--------------------------------------------------------------------------
    |
    | The default URL to use to import the database.
    |
    */

    'import_database_url' => 'https://www.abhayagiri.org/export/abhayagiri-public-latest.sql.bz2',

    /*
    |--------------------------------------------------------------------------
    | Default Import Media URL
    |--------------------------------------------------------------------------
    |
    | The default URL to use to import media.
    |
    */

    'import_media_url' => 'https://www.abhayagiri.org/export/abhayagiri-media-latest.sql.bz2',

    /*
    |--------------------------------------------------------------------------
    | Local Cache Age
    |--------------------------------------------------------------------------
    |
    | The maximum time to consider local exports 'fresh'.
    |
    */

    'cache_age' => 86400, // 1 day

    /*
    |--------------------------------------------------------------------------
    | Keep days
    |--------------------------------------------------------------------------
    |
    | Number of days worth of exports to keep.
    |
    */

    'keep_days' => 5,

    'database' => [

        /*
        |----------------------------------------------------------------------
        | No Data Tables
        |----------------------------------------------------------------------
        |
        | Do not export data for these tables.
        |
        */

        'no_data_tables' => [
            'logs',
            'mahaguild',
            'messages',
            'request',
            'rideshare',
            'tasks',
        ],

        /*
        |----------------------------------------------------------------------
        | Open Status Tables
        |----------------------------------------------------------------------
        |
        | Only export rows with status = 'Open' for these tables.
        |
        */

        'open_status_tables' => [
            'audio',
            'authors',
            'books',
            'columns',
            'construction',
            'danalist',
            'dropdowns',
            'faq',
            'misc',
            'news',
            'options',
            'pages',
            'reflections',
            'residents',
            'schedule',
            'subpages',
            'uploads',
        ],
    ],

    'media' => [

        /*
        |----------------------------------------------------------------------
        | Ignore Media Paths
        |----------------------------------------------------------------------
        |
        | Regular expressions of paths to ignore.
        |
        */

        'ignore' => [
            '_^discs/_',
            '/\\.(avi|epub|mobi|mp3|mp4|ogg|pdf|wma|wmv|zip)$/i',
        ],

        /*
        |----------------------------------------------------------------------
        | Ignore Large Media Files
        |----------------------------------------------------------------------
        |
        | Files that are greater than size or matching extensions will be
        | ignored.
        |
        */

        'max_size' => 300000,
    ],
];
