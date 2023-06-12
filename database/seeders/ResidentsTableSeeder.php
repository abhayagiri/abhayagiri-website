<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResidentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('residents')->insert([
            [
                'id' => 1,
                'slug' => 'ajahn-pasanno',
                'title_en' => 'Ajahn Pasanno',
                'title_th' => 'หลวงพ่อ ปสนฺโน',
                'status' => 'current',
            ],
        ]);
    }
}
