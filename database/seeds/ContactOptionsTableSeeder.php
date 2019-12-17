<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ContactOptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $defaultEmail = Config::get('abhayagiri.mail.contact_to');
        DB::table('contact_options')->insert([
            [
                'id' => 1,
                'name_en' => 'Request an overnight stay',
                'name_th' => null,
                'slug' => 'request-an-overnight-stay',
                'body_en' => 'Come stay',
                'body_th' => null,
                'confirmation_en' => 'Welcome!',
                'confirmation_th' => null,
                'email' => $defaultEmail,
                'active' => true,
                'published' => true,
                'rank' => 1,
            ],
            [
                'id' => 2,
                'name_en' => 'Subscribe to our email lists',
                'name_th' => null,
                'slug' => 'subscribe-to-our-email-lists',
                'body_en' => 'We have lists.',
                'body_th' => null,
                'confirmation_en' => 'Hello',
                'confirmation_th' => null,
                'email' => $defaultEmail,
                'active' => false,
                'published' => true,
                'rank' => 2,
            ],
        ]);
    }
}
