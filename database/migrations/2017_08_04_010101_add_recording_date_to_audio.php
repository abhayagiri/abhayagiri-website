<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRecordingDateToAudio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audio', function (Blueprint $table) {
            $table->datetime('recording_date')->nullable();
            $table->datetime('date')->change();
            $table->string('youtube_id')->nullable()->change();
        });

        DB::statement('UPDATE `audio` SET `recording_date` = `date`');
        DB::statement("UPDATE `audio` SET `youtube_id` = NULL WHERE `youtube_id` = ''");

        Schema::table('audio', function (Blueprint $table) {
            $table->datetime('recording_date')->nullable(false)->change();
        });

        DB::table('columns')->insert([
            'parent' => 39,
            'display_title' => 'Recording Date',
            'title' => 'recording_date',
            'column_type' => 'date',
            'upload_directory' => '',
            'date' => '2013-03-25 12:00:00',
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
        DB::table('columns')
            ->where('parent', '=', 39)
            ->where('title', '=', 'recording_date')
            ->delete();

        Schema::table('audio', function (Blueprint $table) {
            $table->date('date')->change();
            $table->string('youtube_id')->nullable(false)->change();
            $table->dropColumn('recording_date');
        });
    }
}
