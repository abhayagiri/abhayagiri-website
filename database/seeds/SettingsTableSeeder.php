<?php

use Illuminate\Database\Seeder;

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
                "id" => 1,
                "key" => "home.news.count",
                "value" => "3",
            ],
            [
                "id" => 2,
                "key" => "talks.latest.alt.playlist_group_id",
                "value" => "2",
            ],
            [
                "id" => 3,
                "key" => "talks.latest.alt.count",
                "value" => "3",
            ],
            [
                "id" => 4,
                "key" => "talks.latest.main.playlist_group_id",
                "value" => "1",
            ],
            [
                "id" => 5,
                "key" => "talks.latest.main.count",
                "value" => "3",
            ],
            [
                "id" => 15,
                "key" => "contact.preamble_en",
                "value" => "Please use this page to contact the monastery.",
            ],
            [
                "id" => 16,
                "key" => "contact.preamble_th",
                "value" => "กรุณาใช้หน้าเว็บนี้เป็นช่องทางหลักในการติดต่อทางวัด",
            ],
            [
                "id" => 17,
                "key" => "books.request_form_en",
                "value" => "Requests made by individuals are limited to six books per order.",
            ],
            [
                "id" => 18,
                "key" => "books.request_form_th",
                "value" => "คำขอที่ทำโดยบุคคลถูก จำกัด ไว้ที่หกเล่มต่อคำสั่งซื้อ",
            ],
        ]);
    }
}
