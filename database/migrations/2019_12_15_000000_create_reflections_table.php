<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReflectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (app()->env === 'production') return; // SKIP ON PRODUCTION
        Schema::create('reflections', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('language_id')->unsigned();
            $table->integer('author_id')->unsigned();
            $table->string('slug');
            $table->string('title');
            $table->string('alt_title_en')->nullable();
            $table->string('alt_title_th')->nullable();
            $table->mediumtext('body')->nullable();
            $table->boolean('check_translation')->default(1);
            $table->string('image_path')->nullable();
            $table->boolean('draft')->default(0);
            $table->dateTime('posted_at');
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
        Schema::drop('reflections');
    }
}
