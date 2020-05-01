<?php

use Faker\Generator as Faker;

$factory->define(App\Models\ContactOption::class, function (Faker $faker) {
    return [
        'name_en' => $faker->sentence,
        'slug' => $faker->slug,
        'active' => true,
        'published' => true,
        'email' => $faker->safeEmail,
        'body_en' => $faker->paragraph,
        'confirmation_en' => $faker->paragraph,
        'rank' => $faker->numberBetween(0, 99),
    ];
});
