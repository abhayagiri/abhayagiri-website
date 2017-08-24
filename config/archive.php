<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Export Directory
    |--------------------------------------------------------------------------
    |
    | The directory that contains exports.
    |
    */

    'exports_path' => public_path('exports'),

    /*
    |--------------------------------------------------------------------------
    | Backup Directory
    |--------------------------------------------------------------------------
    |
    | The directory that contains private database backups.
    |
    */

    'backup_path' => storage_path('backups'),

    /*
    |--------------------------------------------------------------------------
    | Media Backup Url
    |--------------------------------------------------------------------------
    |
    | The URL destination for the media backup.
    |
    */

    'media_backup_url' => env('MEDIA_BACKUP_URL', null),

    /*
    |--------------------------------------------------------------------------
    | Archive Prefix
    |--------------------------------------------------------------------------
    |
    | The filename prefix for archives.
    |
    */

    'prefix' => 'abhayagiri',

    /*
    |--------------------------------------------------------------------------
    | Import Database URL
    |--------------------------------------------------------------------------
    |
    | The default URL to use to import the database.
    |
    */

    'import_database_url' => 'https://www.abhayagiri.org/exports/abhayagiri-public-database-latest.sql.bz2',

    /*
    |--------------------------------------------------------------------------
    | Import Database Path
    |--------------------------------------------------------------------------
    |
    | The local path to the cached database import.
    |
    */

    'import_database_path' => storage_path('tmp/database.sql.bz2'),

    /*
    |--------------------------------------------------------------------------
    | Default Import Media URL
    |--------------------------------------------------------------------------
    |
    | The default URL to use to import media.
    |
    */

    'import_media_url' => 'https://www.abhayagiri.org/exports/abhayagiri-public-media-latest.tar.bz2',

    /*
    |--------------------------------------------------------------------------
    | Import Media Path
    |--------------------------------------------------------------------------
    |
    | The local path to the cached media import.
    |
    */

    'import_media_path' => storage_path('tmp/media.tar.bz2'),

    /*
    |--------------------------------------------------------------------------
    | Local Import Cache Age
    |--------------------------------------------------------------------------
    |
    | The maximum time to consider local imports 'fresh'.
    |
    */

    'cache_age' => 86400, // 1 day

    /*
    |--------------------------------------------------------------------------
    | Keep days
    |--------------------------------------------------------------------------
    |
    | Number of days worth of backups and exports to keep.
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
            'failed_jobs',
            'jobs',
            'logs',
            'mahaguild',
            'messages',
            'request',
            'rideshare',
            'tasks',
            'users',
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
        |--------------------------------------------------------------------------
        | Temporary Media Base Directory
        |--------------------------------------------------------------------------
        |
        | The temporary directory that contains the output media directory.
        |
        */

        'temp_base_path' => storage_path('tmp'),

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
