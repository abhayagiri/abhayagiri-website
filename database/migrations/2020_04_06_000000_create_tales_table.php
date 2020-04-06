<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tales', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('author_id')->unsigned();
            $table->string('title_en');
            $table->string('title_th')->nullable();
            $table->mediumtext('body_en')->nullable();
            $table->mediumtext('body_th')->nullable();
            $table->boolean('check_translation')->default(1);
            $table->string('image_path')->nullable();
            $table->boolean('draft')->default(0);
            $table->dateTime('posted_at');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('author_id')->references('id')->on('authors')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tales');
    }
}
