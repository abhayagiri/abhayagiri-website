<?php

/*

*/

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddYouTubeToAudio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        # First, delete bad data...
        DB::table('audio')->where('id', '=', 6061)->delete();

        Schema::table('audio', function (Blueprint $table) {
            $table->string('youtube_id');
        });

        DB::table('columns')->insert([
            'parent' => 39,
            'display_title' => 'YouTube ID',
            'title' => 'youtube_id',
            'column_type' => 'text',
            'upload_directory' => '',
            'date' => '2013-03-22 00:00:00',
            'display' => 'yes',
            'user' => 0,
            'status' => 'open',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        # First, delete bad data...
        DB::table('columns')->where('id', '=', 1642)->delete();
        DB::table('columns')->where('id', '=', 53)->delete();
        DB::table('columns')->where('id', '=', 54)->delete();
        DB::table('columns')->where('id', '=', 55)->delete();

        DB::table('columns')
            ->where('parent', '=', 39)
            ->where('title', '=', 'youtube_id')
            ->delete();

        Schema::table('audio', function (Blueprint $table) {
            $table->dropColumn('youtube_id');
        });
    }
}
