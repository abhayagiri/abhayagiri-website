<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("DELETE FROM tag_talk");
        DB::statement("DELETE FROM tags");
        DB::statement("DELETE FROM genres");
        $this->call(GenresTableSeeder::class);
        $this->call(TagsTableSeeder::class);
    }
}
