<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Author;
use App\Models\Book;
use App\Models\Language;
use Faker\Generator as Faker;

$factory->define(Book::class, function (Faker $faker) {
    return [
        'language_id' => function () {
            return Language::first() ?: factory(Language::class)->create()->id;
        },
        'author_id' => function () {
            return Author::first() ?: factory(Author::class)->create()->id;
        },
        'author2_id' => null,
        'title' => $faker->words(3, true),
        'alt_title_en' => $faker->words(3, true),
        'alt_title_th' => $faker->words(3, true),
        'description_en' => $faker->text,
        'description_th' => $faker->text,
        'weight' => $faker->bothify('# oz'),
        'image_path' => null,
        'pdf_path' => null,
        'epub_path' => null,
        'mobi_path' => null,
        'request' => true,
        'draft' => false,
        'published_on' => $faker->date,
        'posted_at' => $faker->datetime,
    ];
});
