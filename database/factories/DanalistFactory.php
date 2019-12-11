<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Danalist;
use Faker\Generator as Faker;

$factory->define(Danalist::class, function (Faker $faker) {
    return [
        'title' => $faker->words(3, true),
        'link' => $faker->url,
        'summary_en' => $faker->sentence,
        'summary_th' => $faker->sentence,
        'listed' => true,
    ];
});
