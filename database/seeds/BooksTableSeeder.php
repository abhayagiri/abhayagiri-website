<?php

use Illuminate\Database\Seeder;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('books')->insert([
            [
                'id' => 1,
                'language_id' => 1,
                'author_id' => 3,
                'author2_id' => null,
                'title' => 'What is Buddhism?',
                'slug' => 'what-is-buddhism',
                'description_en' => 'What is Buddhism offers a very clear and concise overview of Buddhism and its core teachings.',
                'description_th' => null,
                'weight' => '2 oz',
                'image_path' => 'images/books/what-is-buddhism.jpg',
                'pdf_path' => 'books/what-is-buddhism.pdf',
                'epub_path' => null,
                'mobi_path' => null,
                'request' => 1,
                'draft' => 0,
                'published_on' => '2015-12-01',
                'posted_at' => '2015-12-01 20:00:00',
            ],
        ]);
    }
}
