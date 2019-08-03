<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Subpage;
use Faker\Generator as Faker;

$factory->define(Subpage::class, function (Faker $faker) {
    return [
        'page' => $faker->slug,
        'subpath' => $faker->slug,
        'title_en' => $faker->words(3, true),
        'title_th' => $faker->words(3, true),
        'body_en' => $faker->text,
        'body_th' => $faker->text,
        'draft' => false,
        'rank' => $faker->numberBetween(0, 99),
    ];
});

$factory->state(Subpage::class, 'draft', [
    'draft' => true,
]);
