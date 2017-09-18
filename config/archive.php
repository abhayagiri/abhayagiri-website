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

    /*
    |--------------------------------------------------------------------------
    | Process Timeout
    |--------------------------------------------------------------------------
    |
    | The maximum length of time a process should run for.
    |
    */

    'process_timeout' => 60 * 15, // 15 minutes

    'database' => [

        /*
        |----------------------------------------------------------------------
        | No Data Tables
        |----------------------------------------------------------------------
        |
        | Do not export data for these tables.
        |
        */

        '1 = 0' => [
            'failed_jobs',
            'jobs',
            'logs',
            'mahaguild',
            'messages',
            'request',
            'revisions',
            'rideshare',
            'tasks',
            'users',
        ],

        /*
        |----------------------------------------------------------------------
        | Listed Tables
        |----------------------------------------------------------------------
        |
        | Only export rows with LISTED = 1 for these tables.
        |
        */

        'listed = 1' => [
            'danalist',
        ],

        /*
        |----------------------------------------------------------------------
        | Soft Delete Tables
        |----------------------------------------------------------------------
        |
        | Only export rows with deleted_at IS NULL for these tables.
        |
        */

        'deleted_at IS NULL' => [
            'authors',
            'books',
            'languages',
            'news',
            'playlists',
            'reflections',
            'subjects',
            'subject_groups',
            'tags',
            'talk_types',
            'talks',
        ],

        /*
        |----------------------------------------------------------------------
        | Open Status Tables
        |----------------------------------------------------------------------
        |
        | Only export rows with status = 'Open' for these tables.
        |
        */

        'status = \'open\'' => [
            'columns',
            'construction',
            'dropdowns',
            'faq',
            'misc',
            'options',
            'old_books',
            'old_danalist',
            'old_news',
            'old_reflections',
            'pages',
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
