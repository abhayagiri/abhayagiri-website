<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RedirectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('redirects')->insert([
            [
                'from' => 'talks/6483-dhamma-talks-2019',
                'to' => json_encode([
                    'type' => 'Playlist',
                    'id' => 1,
                    'lng' => 'en',
                ]),
            ],
            [
                'from' => 'th/audio/dhamma-talks-2019',
                'to' => json_encode([
                    'type' => 'Playlist',
                    'id' => 1,
                    'lng' => 'th',
                ]),
            ],
        ]);
    }
}
