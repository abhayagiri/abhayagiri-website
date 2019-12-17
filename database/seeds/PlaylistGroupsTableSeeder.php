<?php

use Illuminate\Database\Seeder;

class PlaylistGroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('playlist_groups')->insert([[
            'id' => 1,
            'slug' => 'dhamma-talks',
            'title_en' => 'Dhamma Talks',
            'title_th' => 'รวมพระธรรมเทศนาและข้อคิดจากพระอาจารย์',
            'rank' => 1,
        ], [
            'id' => 2,
            'slug' => 'chanting',
            'title_en' => 'Chanting',
            'title_th' => 'เสียงสวดมนต์',
            'rank' => 2,
        ]]);
    }
}
