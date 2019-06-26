<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Author;
use App\Models\Language;
use App\Models\Talk;
use Faker\Generator as Faker;

$factory->define(Talk::class, function (Faker $faker) {
    return [
        'title_en' => $faker->words(3, true),
        'title_th' => $faker->words(3, true),
        'language_id' => Language::english()->id,
        'author_id' => Author::firstOrFail(),
        'description_en' => $faker->text,
        'description_th' => $faker->text,
        'youtube_id' => $faker->regexify('[A-Za-z0-9_-]{11}'),
        'recorded_on' => $faker->datetime,
        'posted_at' => $faker->date,
    ];
});
