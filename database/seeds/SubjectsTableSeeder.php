<?php

use Illuminate\Database\Seeder;

class SubjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('subjects')->insert([
            'id' => 1,
            'group_id' => 1,
            'slug' => 'suffering',
            'title_en' => 'Suffering',
            'title_th' => 'ความทุกข์',
            'rank' => 1,
        ]);
        DB::table('subjects')->insert([
            'id' => 2,
            'group_id' => 1,
            'slug' => 'aging-sickness-and-death',
            'title_en' => 'Aging, Sickness, and Death',
            'title_th' => 'เกิด แก่ เจ็บ ตาย',
            'rank' => 2,
        ]);
        DB::table('subjects')->insert([
            'id' => 3,
            'group_id' => 2,
            'slug' => 'spiritual-friendships',
            'title_en' => 'Spiritual Friendships',
            'title_th' => 'กัลยาณมิตร',
            'rank' => 1,
        ]);
        DB::table('subjects')->insert([
            'id' => 4,
            'group_id' => 2,
            'slug' => 'generosity',
            'title_en' => 'Generosity',
            'title_th' => 'ทาน',
            'rank' => 2,
        ]);
    }
}
