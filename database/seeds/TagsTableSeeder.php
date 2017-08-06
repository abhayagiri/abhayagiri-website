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
        $genreId1 = DB::table('genres')->where('slug', 'suffering-and-hindrances')->first()->id;
        $genreId2 = DB::table('genres')->where('slug', 'spirital-strengths-and-factors-of-awakening')->first()->id;
        DB::table('tags')->insert([
            'genre_id' => $genreId1,
            'slug' => 'suffering',
            'title_en' => 'Suffering',
            'title_th' => 'ความทุกข์',
            'rank' => 1,
        ]);
        DB::table('tags')->insert([
            'genre_id' => $genreId1,
            'slug' => 'aging-sickness-and-death',
            'title_en' => 'Aging, Sickness, and Death',
            'title_th' => 'เกิด แก่ เจ็บ ตาย',
            'rank' => 2,
        ]);
        DB::table('tags')->insert([
            'genre_id' => $genreId2,
            'slug' => 'spiritual-friendships',
            'title_en' => 'Spiritual Friendships',
            'title_th' => 'กัลยาณมิตร',
            'rank' => 1,
        ]);
        DB::table('tags')->insert([
            'genre_id' => $genreId2,
            'slug' => 'generosity',
            'title_en' => 'Generosity',
            'title_th' => 'ทาน',
            'rank' => 2,
        ]);
    }
}
