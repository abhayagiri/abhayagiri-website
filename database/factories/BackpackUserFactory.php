<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\BackpackUser;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(BackpackUser::class, function (Faker $faker) {
    return factory(User::class)->make()->getAttributes();
});

$factory->state(BackpackUser::class, 'super_admin', [
    'is_super_admin' => true,
]);
