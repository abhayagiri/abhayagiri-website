<?php

use Illuminate\Database\Seeder;

class SubjectGroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('subject_groups')->insert([
            'id' => 1,
            'slug' => 'suffering-and-hindrances',
            'title_en' => 'Suffering and Hindrances',
            'title_th' => 'ความทุกข์ และ อุปสรรค',
            'rank' => 1,
        ]);
        DB::table('subject_groups')->insert([
            'id' => 2,
            'slug' => 'spirital-strengths-and-factors-of-awakening',
            'title_en' => 'Spiritual Strengths and Factors of Awakening',
            'title_th' => 'พละ / อินทรีย์ และ โพชฌงค์',
            'rank' => 2,
        ]);
    }
}
