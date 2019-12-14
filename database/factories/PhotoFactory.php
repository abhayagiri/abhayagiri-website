<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Photo;
use Faker\Generator as Faker;

$factory->define(Photo::class, function (Faker $faker) {
    $n = function ($a, $b) use ($faker) {
        return $faker->numberBetween($a, $b);
    };
    $s = [
        $n(3000, 4000), $n(3000, 4000),
        $n(300, 500), $n(300, 500),
        $n(500, 800), $n(500, 800),
        $n(800, 1500), $n(800, 1500),
    ];
    return [
        'caption_en' => $faker->words(3, true),
        'caption_th' => $faker->words(3, true),
        'original_url' => "https://placekitten.com/$s[0]/$s[1]",
        'original_width' => $s[0],
        'original_height' => $s[1],
        'small_url' => "https://placekitten.com/$s[2]/$s[3]",
        'small_width' => $s[2],
        'small_height' => $s[3],
        'medium_url' => "https://placekitten.com/$s[4]/$s[5]",
        'medium_width' => $s[4],
        'medium_height' => $s[5],
        'large_url' => "https://placekitten.com/$s[6]/$s[7]",
        'large_width' => $s[6],
        'large_height' => $s[7],
    ];
});
