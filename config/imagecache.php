<?php

return [

    /*
    |--------------------------------------------------------------------------
    | ImageCache presets
    |--------------------------------------------------------------------------
    |
    | These are presets with parameters for Glide.
    |
    | See https://glide.thephpleague.com/ for more information these parameters.
    |
    */

    'presets' => [
        'rss' => [
            'w' => 720,
            'h' => 405,
            'fit' => 'cover',
        ],
        'thumb' => [
            'w' => 534,
            'h' => 300,
            'fit' => 'crop',
        ],
        'square' => [
            'w' => 300,
            'h' => 300,
            'fit' => 'crop',
        ],
        'icon' =>  [
            'w' => 300,
            'h' => 300,
            'fit' => 'cover',
        ],
        'article' => [
            'w' => 800,
            'h' => 450,
            'fit' => 'max',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | ImageCache fallback image
    |--------------------------------------------------------------------------
    |
    | The local image to return by ImageCache if the media image is not found.
    |
    */

    'fallbackImage' => public_path('abhayagiri_podcast.jpg'),


    /*
    |--------------------------------------------------------------------------
    | ImageCache cache directory
    |--------------------------------------------------------------------------
    |
    | The local cache directory.
    |
    */

    'cacheDir' => storage_path('app/imagecache'),

];
