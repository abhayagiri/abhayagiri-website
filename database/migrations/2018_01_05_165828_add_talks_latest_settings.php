<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTalksLatestSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $add = function($key, $value) {
            DB::table('settings')->insert([
                'key' => $key,
                'value' => $value,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        };
        $add('talks.latest.alt.playlist_group_id', 10);
        $add('talks.latest.alt.count', 2);
        $add('talks.latest.main.playlist_group_id', 5);
        $add('talks.latest.main.count', 3);
        $add('talks.latest.authors.image_file', 'images/talks/latest/authors.jpg');
        $add('talks.latest.authors.description_en', 'Dhamma talks by teacher');
        $add('talks.latest.authors.description_th', null);
        $add('talks.latest.playlists.image_file', 'images/talks/latest/playlists.jpg');
        $add('talks.latest.playlists.description_en', 'Browse by groups of talks, retreats, chanting, readings');
        $add('talks.latest.playlists.description_th', null);
        $add('talks.latest.subjects.image_file', 'images/talks/latest/subjects.jpg');
        $add('talks.latest.subjects.description_en', 'Browse by themes or topics discussed in the talk: metta, energy, mindfulness, etc.');
        $add('talks.latest.subjects.description_th', null);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('settings')->where('key', '!=', 'home.news.count')->delete();
    }
}
