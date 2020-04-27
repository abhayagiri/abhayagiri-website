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
            'w' => 600,
            'h' => 338,
            'fit' => 'cover',
        ],
        'thumb' =>  [
            'w' => 320,
            'h' => 216,
            'fit' => 'crop',
        ],
        'square' =>  [
            'w' => 200,
            'h' => 200,
            'fit' => 'crop',
        ],
        'icon' =>  [
            'w' => 200,
            'h' => 200,
            'fit' => 'cover',
        ],
        'article' => [
            'w' => 768,
            'h' => 432,
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
