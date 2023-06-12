<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('languages')->insert([[
            'id' => 1,
            'code' => 'en',
            'title_en' => 'English',
            'title_th' => 'อังกฤษ ',
        ], [
            'id' => 2,
            'code' => 'th',
            'title_en' => 'Thai',
            'title_th' => 'ไทย',
        ]]);
    }
}
