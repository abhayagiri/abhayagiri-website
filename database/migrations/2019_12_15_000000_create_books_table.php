<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (app()->env === 'production') return; // SKIP ON PRODUCTION
        Schema::create('books', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('language_id')->unsigned();
            $table->integer('author_id')->unsigned();
            $table->integer('author2_id')->unsigned()->nullable();
            $table->string('slug');
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('alt_title_en')->nullable();
            $table->string('alt_title_th')->nullable();
            $table->mediumtext('description_en')->nullable();
            $table->mediumtext('description_th')->nullable();
            $table->boolean('check_translation')->default(1);
            $table->string('weight')->nullable();
            $table->string('image_path')->nullable();
            $table->string('pdf_path')->nullable();
            $table->string('epub_path')->nullable();
            $table->string('mobi_path')->nullable();
            $table->boolean('request')->default(1);
            $table->boolean('draft')->default(0);
            $table->date('published_on');
            $table->dateTime('posted_at');
            $table->softDeletes();
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
        Schema::drop('books');
    }
}
