<?php

use Illuminate\Database\Seeder;

class PlaylistTalkTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('playlist_talk')->insert([
            [
                'playlist_id' => 1,
                'talk_id' => 1,
            ],
        ]);
    }
}
