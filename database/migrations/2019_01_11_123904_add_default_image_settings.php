<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class AddDefaultImageSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $add = function ($key, $value) {
            DB::table('settings')->insert([
                'key' => $key,
                'value' => $value,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        };

        $add('authors.default_image_file', null);
        $add('books.default_image_file', null);
        $add('news.default_image_file', null);
        $add('playlists.default_image_file', null);
        $add('playlistgroups.default_image_file', null);
        $add('reflections.default_image_file', null);
        $add('residents.default_image_file', null);
        $add('subjects.default_image_file', null);
        $add('subjectgroups.default_image_file', null);
        $add('talks.default_image_file', null);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('settings')->where('key', 'like', '%.default_image_file')->delete();
    }
}
