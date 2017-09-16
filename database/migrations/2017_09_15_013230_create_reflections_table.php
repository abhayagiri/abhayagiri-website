<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReflectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('reflections', 'old_reflections');
        Schema::create('reflections', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('language_id');
            $table->unsignedInteger('author_id');
            $table->string('slug');
            $table->string('title');
            $table->string('alt_title_en')->nullable();
            $table->string('alt_title_th')->nullable();
            $table->text('body')->nullable();
            $table->boolean('check_translation')->default(true);
            $table->string('image_path')->nullable();
            $table->boolean('draft')->default(false);
            $table->datetime('posted_at');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('language_id')->references('id')
                ->on('languages')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('reflections');
        Schema::rename('old_reflections', 'reflections');
    }
}
