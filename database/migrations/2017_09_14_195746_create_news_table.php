<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('news', 'old_news');

        Schema::create('news', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug');
            $table->string('title_en');
            $table->string('title_th')->nullable();
            $table->text('body_en')->nullable();
            $table->text('body_th')->nullable();
            $table->boolean('check_translation')->default(true);
            $table->string('image_path')->nullable();
            $table->boolean('draft')->default(false);
            $table->datetime('posted_at');
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
        Schema::drop('news');

        Schema::rename('old_news', 'news');
    }
}
