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
        DB::statement("DELETE FROM subject_tag");
        DB::statement("DELETE FROM tags");
        DB::statement("DELETE FROM subjects");
        DB::statement("DELETE FROM subject_groups");
        $this->call(SubjectGroupTableSeeder::class);
        $this->call(SubjectTableSeeder::class);
        $this->call(TagTableSeeder::class);
    }
}
