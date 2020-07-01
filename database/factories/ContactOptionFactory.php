<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\ContactOption;
use Faker\Generator as Faker;

$factory->define(ContactOption::class, function (Faker $faker) {
    return [
        'name_en' => $faker->sentence,
        'name_th' => $faker->sentence,
        'slug' => $faker->slug,
        'active' => true,
        'published' => true,
        'email' => $faker->safeEmail,
        'body_en' => $faker->paragraph,
        'body_th' => $faker->paragraph,
        'confirmation_en' => $faker->paragraph,
        'confirmation_th' => $faker->paragraph,
        'rank' => $faker->numberBetween(0, 99),
    ];
});
