<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaylistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (app()->env === 'production') return; // SKIP ON PRODUCTION
        Schema::create('playlists', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('group_id')->unsigned()->nullable();
            $table->string('slug')->unique();
            $table->string('title_en');
            $table->string('title_th')->nullable();
            $table->mediumtext('description_en')->nullable();
            $table->mediumtext('description_th')->nullable();
            $table->boolean('check_translation')->default(1);
            $table->string('youtube_playlist_id')->collation('utf8mb4_bin')->nullable()->unique();
            $table->string('image_path')->nullable();
            $table->integer('rank')->unsigned()->default(0);
            $table->dateTime('posted_at');
            $table->boolean('draft');
            $table->timestamps();
            $table->softDeletes();
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
        Schema::drop('playlists');
    }
}
