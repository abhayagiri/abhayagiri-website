<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaylistGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (app()->env === 'production') return; // SKIP ON PRODUCTION
        Schema::create('playlist_groups', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('slug');
            $table->string('title_en');
            $table->string('title_th')->nullable();
            $table->mediumtext('description_en')->nullable();
            $table->mediumtext('description_th')->nullable();
            $table->boolean('check_translation')->default(1);
            $table->string('image_path')->nullable();
            $table->integer('rank')->unsigned()->default(0);
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
        Schema::drop('playlist_groups');
    }
}
