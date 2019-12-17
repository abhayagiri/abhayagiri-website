<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Setting;
use Faker\Generator as Faker;

$factory->define(Setting::class, function (Faker $faker) {
    return [
        'key' => $faker->uuid,
        'value' => $faker->numberBetween(0, 10),
    ];
});
