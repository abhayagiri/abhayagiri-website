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
    'dir' => null, #['media'],

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
        'spaces' => [
            'alias' => 'media',
            'URL' => env('DO_SPACES_URL') . '/media',
            'path' => 'media',
            // This is workaround to avoid n+1 queries to Digital Ocean Spaces
            // during directory listings. This tells elFinder to use the
            // App\Utilities\elFinderDigitalOceanSpacesDriver class (a.k.a.
            // elFinderVolumeDigitalOceanSpaces) instead of
            // Barryvdh\elFinderFlysystemDriver\Driver.
            'driver' => 'DigitalOceanSpaces',
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

];
