<?php

use Illuminate\Database\Seeder;

class PlaylistsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('playlists')->insert([[
            'id' => 1,
            'group_id' => 1,
            'slug' => 'dhamma-talks-2019',
            'title_en' => 'Dhamma Talks 2019',
            'title_th' => 'รวมพระธรรมเทศนา พ.ศ. ๒๕๖๒',
            'description_en' => 'Dhamma talks from Abhayagiri for 2019',
            'description_th' => 'รวมพระธรรมเทศนาและข้อคิดจากพระอาจารย์',
            'youtube_playlist_id' => 'PLoNO26iBjavZU97drGeGjsSrykV7sxZp',
            'rank' => 1,
            'draft' => 0,
            'posted_at' => '2019-01-09 19:38:53',
        ]]);
    }
}
