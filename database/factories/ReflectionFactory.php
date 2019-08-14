<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Author;
use App\Models\Language;
use App\Models\Reflection;
use Faker\Generator as Faker;

$factory->define(Reflection::class, function (Faker $faker) {
    return [
        'author_id' => factory(Author::class)->create()->id,
        'language_id' => Language::english()->id,
        'title' => $faker->words(3, true),
        'alt_title_en' => $faker->words(3, true),
        'alt_title_th' => $faker->words(3, true),
        'body' => $faker->text,
        'draft' => false,
        'posted_at' => $faker->date,
    ];
});

$factory->state(Reflection::class, 'draft', [
    'draft' => true,
]);
