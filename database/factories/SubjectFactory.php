<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Subject;
use App\Models\SubjectGroup;
use Faker\Generator as Faker;

$factory->define(Subject::class, function (Faker $faker) {
    return [
        'group_id' => function () {
            return factory(SubjectGroup::class)->create()->id;
        },
        'title_en' => $faker->words(3, true),
        'title_th' => $faker->words(3, true),
        'description_en' => $faker->text,
        'description_th' => $faker->text,
    ];
});
