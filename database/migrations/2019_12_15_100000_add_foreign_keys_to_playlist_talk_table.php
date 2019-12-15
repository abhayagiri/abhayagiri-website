<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPlaylistTalkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (app()->env === 'production') return; // SKIP ON PRODUCTION
        Schema::table('playlist_talk', function(Blueprint $table)
        {
            $table->foreign('playlist_id')->references('id')->on('playlists')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('talk_id')->references('id')->on('talks')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (app()->env === 'production') return; // SKIP ON PRODUCTION
        Schema::table('playlist_talk', function(Blueprint $table)
        {
            $table->dropForeign('playlist_talk_playlist_id_foreign');
            $table->dropForeign('playlist_talk_talk_id_foreign');
        });
    }
}
