<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlbumsAndPhotos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->nullable();
            $table->string('caption_en')->nullable();
            $table->string('caption_th')->nullable();
            $table->string('original_url');
            $table->unsignedInteger('original_width');
            $table->unsignedInteger('original_height');
            $table->string('small_url');
            $table->unsignedInteger('small_width');
            $table->unsignedInteger('small_height');
            $table->string('large_url');
            $table->unsignedInteger('large_width');
            $table->unsignedInteger('large_height');
            $table->timestamps();
        });

        Schema::create('albums', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug');
            $table->string('title_en');
            $table->string('title_th')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_th')->nullable();
            $table->unsignedInteger('thumbnail_id');
            $table->unsignedInteger('rank')->default(0);
            $table->timestamps();
            $table->foreign('thumbnail_id')->references('id')
                ->on('photos')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });

        Schema::create('album_photo', function (Blueprint $table) {
            $table->unsignedInteger('album_id');
            $table->unsignedInteger('photo_id');
            $table->unsignedInteger('rank')->default(0);
            $table->timestamps();
            $table->foreign('album_id')->references('id')
                ->on('albums')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('photo_id')->references('id')
                ->on('photos')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('album_photo');
        Schema::drop('albums');
        Schema::drop('photos');
    }
}
