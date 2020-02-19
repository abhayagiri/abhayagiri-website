<?php

use App\Models\Book;
use App\Models\News;
use App\Models\Subpage;
use App\Models\Reflection;

return [
    /*
    |--------------------------------------------------------------------------
    | Model Rank
    |--------------------------------------------------------------------------
    |
    | Used to determine the model_rank value for the scout searchable array
    | to affect how search results are ordered to favor certain searchable
    | models over other models.
    |
    */
    'model_ranks' => [
        Book::class,
        News::class,
        Subpage::class,
        Reflection::class,
    ],
];
