<?php

use Illuminate\Database\Seeder;

class TalksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('talks')->insert([[
            'id' => 1,
            'title_en' => 'Running Away from Phantoms',
            'title_th' => 'หลบหนีจากสิ่งลวงตา',
            'slug' => 'running-away-from-phantoms',
            'language_id' => 1,
            'author_id' => 5,
            'description_en' => "Ajahn Sucitto offers reflections on the process of running away and towards the preoccupations of the mind. He discusses the process of seeing this process without value judgments and analysis and developing balance as a reference point. He reviews how the mind frames the world, the real and imagined dangers of existing in the world and dealing directly with the fear that rises.\r\n\r\nThis Dhamma talk was offered at Abhayagiri Buddhist Monastery on May 26, 2007.",
            'description_th' => "อาจารย์สุจิตโตแสดงธรรมเรื่องกระบวนการหลบหนีและการมุ่งสู่ความฝังใจ ท่านพูดถึงการเฝ้าดูกระบวนนี้โดยปราศจากการวิเคาระห์ตัดสิน และพัฒนาความสมดุลขึ้นเพื่อที่จะเป็นจุดอ้างอิง ท่านกล่าวถึงวิธีการที่จิต วางกรอบโลก ความจริงและอันตรายที่ปรุงแต่งต่อการดำรงอยู่ในโลก และความสัมพันธ์โดยตรงต่อความกลัวที่เกิดขึ้น\r\n\r\nธรรมเทศนานี้แสดงที่วัดป่าอภัยคีรีในวันที่ 26 พฤษภาคม 2007",
            'youtube_video_id' => 'ZQO41hjkrCE',
            'recorded_on' => '2007-05-26',
            'posted_at' => '2017-12-13 19:00:00',
        ]]);
    }
}
