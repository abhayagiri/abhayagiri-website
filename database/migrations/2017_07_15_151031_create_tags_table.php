<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('genre_id');
            $table->string('title_en');
            $table->string('title_th')->nullable();
            $table->boolean('check_translation')->default(false);
            $table->string('image_path')->nullable();
            $table->unsignedInteger('rank')->default(0);
            $table->timestamps();
            $table->foreign('genre_id')->references('id')
                ->on('genres')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tags');
    }
}
