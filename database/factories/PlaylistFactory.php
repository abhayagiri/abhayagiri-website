<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Playlist;
use App\Models\PlaylistGroup;
use Faker\Generator as Faker;

$factory->define(Playlist::class, function (Faker $faker) {
    return [
        'group_id' => function () {
            return factory(PlaylistGroup::class)->create()->id;
        },
        'title_en' => $faker->words(3, true),
        'title_th' => $faker->words(3, true),
        'description_en' => $faker->text,
        'description_th' => $faker->text,
        'youtube_playlist_id' => $faker->regexify('[A-Za-z0-9_-]{34}'),
    ];
});
