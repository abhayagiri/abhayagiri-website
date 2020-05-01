<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Author;
use App\Models\Tale;
use Faker\Generator as Faker;

$factory->define(Tale::class, function (Faker $faker) {
    return [
        'author_id' => factory(Author::class)->create()->id,
        'title_en' => $faker->words(3, true),
        'title_th' => $faker->words(3, true),
        'body_en' => $faker->text,
        'body_th' => $faker->text,
        'draft' => false,
        'posted_at' => $faker->date,
    ];
});

$factory->state(Tale::class, 'draft', [
    'draft' => true,
]);
