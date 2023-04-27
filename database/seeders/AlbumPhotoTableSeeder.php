<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlbumPhotoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('album_photo')->insert([
            [
                'album_id' => 1,
                'photo_id' => 1,
                'rank' => 1,
            ],
        ]);
    }
}
