<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Album;
use App\Models\Photo;
use Faker\Generator as Faker;

$factory->define(Album::class, function (Faker $faker) {
    return [
        'title_en' => $faker->words(3, true),
        'title_th' => $faker->words(3, true),
        'description_en' => $faker->text,
        'description_th' => $faker->text,
        'thumbnail_id' => factory(Photo::class)->create()->id,
        'rank' => $faker->numberBetween(1, 1000),
    ];
});
