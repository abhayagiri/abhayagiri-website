<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Resident;
use Faker\Generator as Faker;

$factory->define(Resident::class, function (Faker $faker) {
    return [
        'slug' => $faker->slug,
        'title_en' => $faker->words(2, true),
        'title_th' => $faker->words(2, true),
        'description_en' => $faker->text,
        'description_th' => $faker->text,
        'rank' => $faker->numberBetween(1, 1000),
        'status' => $faker->randomElement(['current', 'traveling']),
    ];
});
