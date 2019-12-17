<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (app()->env === 'production') return; // SKIP ON PRODUCTION
        Schema::create('albums', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('slug');
            $table->string('title_en');
            $table->string('title_th')->nullable();
            $table->mediumtext('description_en')->nullable();
            $table->mediumtext('description_th')->nullable();
            $table->integer('thumbnail_id')->unsigned();
            $table->integer('rank')->unsigned()->default(0);
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
        Schema::drop('albums');
    }
}
