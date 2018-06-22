<?php

use Faker\Generator as Faker;

$factory->define(App\Models\ContactOption::class, function (Faker $faker) {
    return [
        'name_en' => $faker->sentence,
        'active' => true,
        'published' => true,
        'email' => $faker->safeEmail,
        'body_en' => $faker->paragraph,
    ];
});
