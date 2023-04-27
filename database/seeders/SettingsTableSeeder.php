<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            [
                'id' => 1,
                'type' => 'number',
                'key' => 'home.news_count',
                'value' => '3',
            ],
            [
                'id' => 2,
                'type' => 'playlist_group',
                'key' => 'talks.latest.alt_playlist_group',
                'value' => '2',
            ],
            [
                'id' => 3,
                'type' => 'number',
                'key' => 'talks.latest.alt_count',
                'value' => '3',
            ],
            [
                'id' => 4,
                'type' => 'playlist_group',
                'key' => 'talks.latest.main_playlist_group',
                'value' => '1',
            ],
            [
                'id' => 5,
                'type' => 'number',
                'key' => 'talks.latest.main_count',
                'value' => '3',
            ],
            [
                'id' => 15,
                'type' => 'markdown',
                'key' => 'contact.preamble',
                'value' => '{"text_en":"Please use this page to contact the monastery.",' .
                            '"text_th":"กรุณาใช้หน้าเว็บนี้เป็นช่องทางหลักในการติดต่อทางวัด"}',
            ],
            [
                'id' => 17,
                'type' => 'markdown',
                'key' => 'books.request_form',
                'value' => '{"text_en":"Requests made by individuals are limited to six books per order.",' .
                            '"text_th":"คำขอที่ทำโดยบุคคลถูก จำกัด ไว้ที่หกเล่มต่อคำสั่งซื้อ"}',
            ],
        ]);
    }
}
