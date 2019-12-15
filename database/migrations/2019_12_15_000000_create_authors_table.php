<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (app()->env === 'production') return; // SKIP ON PRODUCTION
        Schema::create('authors', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('slug')->nullable()->unique('authors_url_title_unique');
            $table->string('title_en');
            $table->string('title_th')->nullable();
            $table->boolean('visiting')->default(0);
            $table->boolean('check_translation')->default(1);
            $table->string('image_path')->nullable();
            $table->integer('rank')->unsigned()->default(1000);
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
        Schema::drop('authors');
    }
}
