<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class AlbumsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('albums')->insert([
            [
                'id' => 1,
                'slug' => 'winter-to-spring',
                'title_en' => 'Winter to Spring',
                'title_th' => 'ฤดูหนาวถึงฤดูใบไม้ผลิ',
                'description_en' => 'Bright flowers, green grass, and big clouds. Spring returns.',
                'description_th' => 'ดอกไม้ที่สดใสหญ้าสีเขียวและเมฆก้อนโต ฤดูใบไม้ผลิกลับมา',
                'thumbnail_id' => 1,
                'rank' => 1,
            ],
        ]);
    }
}
