<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReflectionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('reflections')->insert([
            [
                'id' => 1,
                'language_id' => 1,
                'author_id' => 1,
                'slug' => 'generosity-of-heart',
                'title' => 'Generosity of Heart',
                'body' => "When we are working together and interacting with each other, it's so important for a community to have mutual respect for each other and recognize that everybody is here because they have the intention to do something good, something wholesome.",
                'draft' => 0,
                'posted_at' => '2012-08-27 19:00:00',
            ],
        ]);
    }
}
