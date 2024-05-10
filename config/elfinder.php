<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Upload dir
    |--------------------------------------------------------------------------
    |
    | The dir where to store the images (relative from public).
    |
    */
    'dir' => null, // #['media'],

    /*
    |--------------------------------------------------------------------------
    | Filesystem disks (Flysytem)
    |--------------------------------------------------------------------------
    |
    | Define an array of Filesystem disks, which use Flysystem.
    | You can set extra options, example:
    |
    | 'my-disk' => [
    |        'URL' => url('to/disk'),
    |        'alias' => 'Local storage',
    |    ]
    */
    'disks' => env('DO_SPACES_URL') ? [
        'spaces-media' => [
            'URL' => env('DO_SPACES_URL') . '/media',
            'alias' => 'media',
            'path' => 'media',
            // HACK: this won't work if we go beyond 1 server, but it's the
            // only way it seems to get thumbnails generating properly
            'tmbPath' => public_path('img/elfinder-tmb'),
            'tmbURL' => env('APP_URL') . '/img/elfinder-tmb',
        ],
    ] : null,

    /*
    |--------------------------------------------------------------------------
    | Routes group config
    |--------------------------------------------------------------------------
    |
    | The default group settings for the elFinder routes.
    |
    */

    'route' => [
        'prefix'     => config('backpack.base.route_prefix', 'admin').'/elfinder',
        'middleware' => [
            'web',
            config('backpack.base.middleware_key', 'admin'),
            'long_running',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Access filter
    |--------------------------------------------------------------------------
    |
    | Filter callback to check the files
    |
    */

    'access' => 'Barryvdh\Elfinder\Elfinder::checkAccess',

    /*
    |--------------------------------------------------------------------------
    | Roots
    |--------------------------------------------------------------------------
    |
    | By default, the roots file is LocalFileSystem, with the above public dir.
    | If you want custom options, you can set your own roots below.
    |
    */

    'roots' => env('DO_SPACES_URL') ? null : [
        [
            'driver'        => 'LocalFileSystem',
            'path'          => public_path('media'),
            'URL'           => env('APP_URL') . '/media',
            'accessControl' => 'Barryvdh\Elfinder\Elfinder::checkAccess',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Options
    |--------------------------------------------------------------------------
    |
    | These options are merged, together with 'roots' and passed to the Connector.
    | See https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options-2.1
    |
    */

    'options' => [],

    /*
    |--------------------------------------------------------------------------
    | Root Options
    |--------------------------------------------------------------------------
    |
    | These options are merged, together with every root by default.
    | See https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options-2.1#root-options
    |
    */

    'root_options' => [
        // The default mimeDetect strategy is auto (i.e., finfo_file) is
        // painfully slow when there are many MP3 files in a directory. We
        // change this to internal as it does a quick heuristic based on
        // the filename.
        'mimeDetect' => 'internal',
    ],

    /*
    |--------------------------------------------------------------------------
    | Maximum Thumb Generation
    |--------------------------------------------------------------------------
    |
    | Due to problems/slowlness with thumbnails on Digital Ocean Spaces, this
    | imposes a hard limit on the maximum number of files in a directory before
    | it stops generating thumbnails.
    |
    */

    'max_thumbnail_directory_size' => 400,

];
