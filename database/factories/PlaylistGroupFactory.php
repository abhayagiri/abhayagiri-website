<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\PlaylistGroup;
use Faker\Generator as Faker;

$factory->define(PlaylistGroup::class, function (Faker $faker) {
    return [
        'title_en' => $faker->words(3, true),
        'title_th' => $faker->words(3, true),
        'description_en' => $faker->text,
        'description_th' => $faker->text,
    ];
});
