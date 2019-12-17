<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\News;
use Faker\Generator as Faker;

$factory->define(News::class, function (Faker $faker) {
    return [
        'title_en' => $faker->words(3, true),
        'title_th' => $faker->words(3, true),
        'body_en' => $faker->text,
        'body_th' => $faker->text,
        'rank' => $faker->boolean ? $faker->numberBetween(1, 5) : null,
        'posted_at' => $faker->datetime,
    ];
});
