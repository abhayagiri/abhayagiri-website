<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('books', 'old_books');

        Schema::create('books', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('language_id');
            $table->unsignedInteger('author_id');
            $table->unsignedInteger('author2_id')->nullable();
            $table->string('slug');
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('alt_title_en')->nullable();
            $table->string('alt_title_th')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_th')->nullable();
            $table->boolean('check_translation')->default(true);
            $table->string('weight')->nullable();
            $table->string('image_path')->nullable();
            $table->string('pdf_path')->nullable();
            $table->string('epub_path')->nullable();
            $table->string('mobi_path')->nullable();
            $table->boolean('request')->default(true);
            $table->boolean('draft')->default(false);
            $table->date('published_on');
            $table->datetime('posted_at');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('language_id')->references('id')
                ->on('languages')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('author_id')->references('id')
                ->on('authors')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('author2_id')->references('id')
                ->on('authors')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('books');
        Schema::rename('old_books', 'books');
    }
}
