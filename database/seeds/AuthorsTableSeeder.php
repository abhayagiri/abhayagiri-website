<?php

use Illuminate\Database\Seeder;

class AuthorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('authors')->insert([[
            'id' => 1,
            'slug' => 'ajahn-pasanno',
            'title_en' => 'Ajahn Pasanno',
            'title_th' => 'อาจารย์ ปสันโน',
            'visiting' => 0,
        ], [
            'id' => 2,
            'slug' => 'ajahn-amaro',
            'title_en' => 'Ajahn Amaro',
            'title_th' => 'อาจารย์ อมโร',
            'visiting' => 0,
        ], [
            'id' => 3,
            'slug' => 'abhayagiri-sangha',
            'title_en' => 'Abhayagiri Sangha',
            'title_th' => 'คณะสงฆ์วัดอภัยคีรี',
            'visiting' => 0,
        ], [
            'id' => 4,
            'slug' => 'ajahn-liem',
            'title_en' => 'Ajahn Liem',
            'title_th' => 'อาจารย์ เลี่ยม',
            'visiting' => 1,
        ], [
            'id' => 5,
            'slug' => 'ajahn-sucitto',
            'title_en' => 'Ajahn Sucitto',
            'title_th' => 'อาจารย์ สุจิตโต',
            'visiting' => 1,
        ]]);
    }
}
