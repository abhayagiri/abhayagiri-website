<?php

use Illuminate\Database\Seeder;

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
