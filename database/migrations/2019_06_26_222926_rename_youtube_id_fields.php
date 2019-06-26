<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameYoutubeIdFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('playlists', function (Blueprint $table) {
            $table->dropUnique('playlists_youtube_id_unique');
            $table->renameColumn('youtube_id', 'youtube_playlist_id');
            $table->unique('youtube_playlist_id');
        });
        Schema::table('playlists', function (Blueprint $table) {
            $table->string('youtube_playlist_id')->collation('utf8mb4_bin')->change();
        });
        Schema::table('talks', function (Blueprint $table) {
            $table->dropUnique('talks_youtube_id_unique');
            $table->renameColumn('youtube_id', 'youtube_video_id');
            $table->unique('youtube_video_id');
        });
        Schema::table('talks', function (Blueprint $table) {
            $table->string('youtube_video_id')->collation('utf8mb4_bin')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('playlists', function (Blueprint $table) {
            $table->dropUnique('playlists_youtube_playlist_id_unique');
            $table->renameColumn('youtube_playlist_id', 'youtube_id');
            $table->unique('youtube_id');
        });
        Schema::table('playlists', function (Blueprint $table) {
            $table->string('youtube_id')->collation('utf8mb4_bin')->change();
        });
        Schema::table('talks', function (Blueprint $table) {
            $table->dropUnique('talks_youtube_video_id_unique');
            $table->renameColumn('youtube_video_id', 'youtube_id');
            $table->unique('youtube_id');
        });
        Schema::table('talks', function (Blueprint $table) {
            $table->string('youtube_id')->collation('utf8mb4_bin')->change();
        });
    }
}
