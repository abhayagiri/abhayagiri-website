<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Redirect;
use Faker\Generator as Faker;

$factory->define(Redirect::class, function (Faker $faker) {
    return [
        'from' => $faker->uuid,
        'to' => json_encode(['type' => 'Basic', 'url' => $faker->url]),
    ];
});
