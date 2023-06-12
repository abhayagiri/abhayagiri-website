<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TalesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tales')->insert([
            [
                'id' => 1,
                'author_id' => 1,
                'title_en' => 'Alone',
                'title_th' => 'คนเดียว',
                'body_en' => 'The weather was not too hot, not too cold, and almost every morning the clouds and cool mist combined with the rising sun produced phenomenal double rainbows. Feeling refreshed and balanced, I would go into the cave and meditate for 2-3 hours.',
                'body_th' => 'สภาพอากาศไม่ร้อนเกินไปไม่เย็นเกินไปและเกือบทุกเช้าเมฆและหมอกเย็น ๆ รวมกับดวงอาทิตย์ขึ้นทำให้เกิดรุ้งสองชั้นที่น่าอัศจรรย์ ฉันรู้สึกสดชื่นและสมดุลฉันจะเข้าไปในถ้ำและนั่งสมาธิเป็นเวลา 2-3 ชั่วโมง',
                'image_path' => null,
                'draft' => 0,
                'posted_at' => '2012-12-04 19:00:00',
            ],
        ]);
    }
}
