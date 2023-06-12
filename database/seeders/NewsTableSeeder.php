<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('news')->insert([
            [
                'id' => 1,
                'slug' => 'winter-retreat',
                'title_en' => 'Winter Retreat',
                'title_th' => 'การเข้ากรรมฐานฤดูหนาว',
                'body_en' => 'Beginning January 1st and continuing until March 31st, Abhayagiri will be in winter retreat.',
                'body_th' => '๑ มกราคม จนถึงวันที่ ๓๑ มีนาคม คณะสงฆ์วัดอภัยคีรีจะเริ่มเข้ากรรมฐานฤดูหนาว',
                'draft' => 0,
                'posted_at' => '2015-01-01 20:00:00',
            ],
        ]);
    }
}
