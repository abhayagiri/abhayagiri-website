<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhotosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('photos')->insert([
            [
                'id' => 1,
                'slug' => 'kitten',
                'caption_en' => 'Kitten',
                'caption_th' => 'ลูกแมว',
                'original_url' => 'https://placekitten.com/4000/3000',
                'original_width' => 4000,
                'original_height' => 3000,
                'small_url' => 'https://placekitten.com/500/375',
                'small_width' => 500,
                'small_height' => 375,
                'medium_url' => 'https://placekitten.com/1000/750',
                'medium_width' => 1000,
                'medium_height' => 750,
                'large_url' => 'https://placekitten.com/2000/1500',
                'large_width' => 2000,
                'large_height' => 1500,
            ],
        ]);
    }
}
