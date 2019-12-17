<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTalksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (app()->env === 'production') return; // SKIP ON PRODUCTION
        Schema::create('talks', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('language_id')->unsigned();
            $table->string('title_en');
            $table->string('title_th')->nullable();
            $table->integer('author_id')->unsigned();
            $table->string('slug');
            $table->dateTime('posted_at');
            $table->mediumtext('description_en')->nullable();
            $table->mediumtext('description_th')->nullable();
            $table->boolean('check_translation')->default(1);
            $table->string('image_path')->nullable();
            $table->string('media_path')->nullable();
            $table->string('youtube_video_id')->collation('utf8mb4_bin')->nullable()->unique();
            $table->date('recorded_on');
            $table->boolean('hide_from_latest')->default(0);
            $table->boolean('draft')->default(0);
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
        Schema::drop('talks');
    }
}
