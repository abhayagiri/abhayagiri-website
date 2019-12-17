<?php

use Illuminate\Database\Seeder;

class SubpagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('subpages')->insert([
            [
                'id' => 1,
                'page' => 'community',
                'subpath' => 'residents',
                'title_en' => 'Residents',
                'title_th' => 'พระภิกษุสงฆ์ นักบวชและอุบาสิกา',
                'body_en' => '[!residents]',
                'body_th' => '[!residents]',
                'posted_at' => '2015-01-01 00:00:00',
            ],
            [
                'id' => 2,
                'path' => 'visiting',
                'subpath' => 'directions',
                'title_en' => 'Directions',
                'title_th' => 'เส้นทางมาวัด',
                'body_en' => "Abhayagiri Buddhist Monastery\n16201 Tomki Road\nRedwood Valley, CA 95470",
                'body_th' => null,
                'posted_at' => '2016-01-01 00:00:00',
            ],
        ]);
    }
}
