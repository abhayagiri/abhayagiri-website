<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (app()->env === 'production') return; // SKIP ON PRODUCTION
        Schema::create('photos', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('slug')->nullable();
            $table->string('caption_en')->nullable();
            $table->string('caption_th')->nullable();
            $table->string('original_url');
            $table->integer('original_width')->unsigned();
            $table->integer('original_height')->unsigned();
            $table->string('small_url');
            $table->integer('small_width')->unsigned();
            $table->integer('small_height')->unsigned();
            $table->string('medium_url');
            $table->integer('medium_width')->unsigned();
            $table->integer('medium_height')->unsigned();
            $table->string('large_url');
            $table->integer('large_width')->unsigned();
            $table->integer('large_height')->unsigned();
            $table->timestamps();
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
        Schema::drop('photos');
    }
}
