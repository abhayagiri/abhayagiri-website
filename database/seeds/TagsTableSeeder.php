<?php

use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tags')->insert([
            'id' => 1,
            'slug' => 'suffering',
            'title_en' => 'Suffering',
            'title_th' => 'ความทุกข์',
        ]);
        DB::table('subject_tag')->insert(['subject_id' => 1, 'tag_id' => 1]);
        DB::table('tags')->insert([
            'id' => 2,
            'slug' => 'aging',
            'title_en' => 'Aging',
            'title_th' => 'เกิด แก่',
        ]);
        DB::table('subject_tag')->insert(['subject_id' => 2, 'tag_id' => 2]);
        DB::table('tags')->insert([
            'id' => 3,
            'slug' => 'sickness',
            'title_en' => 'Sickness',
            'title_th' => 'เจ็บ',
        ]);
        DB::table('subject_tag')->insert(['subject_id' => 2, 'tag_id' => 3]);
        DB::table('tags')->insert([
            'id' => 4,
            'slug' => 'death',
            'title_en' => 'Death',
            'title_th' => 'ตาย',
        ]);
        DB::table('subject_tag')->insert(['subject_id' => 2, 'tag_id' => 4]);
        DB::table('tags')->insert([
            'id' => 5,
            'slug' => 'spiritual-friendships',
            'title_en' => 'Spiritual Friendships',
            'title_th' => 'กัลยาณมิตร',
        ]);
        DB::table('subject_tag')->insert(['subject_id' => 3, 'tag_id' => 5]);
        DB::table('tags')->insert([
            'id' => 6,
            'slug' => 'generosity',
            'title_en' => 'Generosity',
            'title_th' => 'ทาน',
        ]);
        DB::table('subject_tag')->insert(['subject_id' => 3, 'tag_id' => 6]);
    }
}
