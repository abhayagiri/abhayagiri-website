<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Language;
use Faker\Generator as Faker;

$factory->define(Language::class, function (Faker $faker) {
    return [
        'code' => $faker->unique()->lexify('????'),
        'title_en' => $faker->words(2, true),
        'title_th' => $faker->words(2, true),
    ];
});
