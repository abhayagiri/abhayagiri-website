<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagTalkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tag_talk', function (Blueprint $table) {
            $table->unsignedInteger('tag_id');
            $table->unsignedInteger('talk_id');
            $table->timestamps();
            $table->foreign('tag_id')->references('id')
                ->on('tags')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('talk_id')->references('id')
                ->on('audio')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tag_talk');
    }
}
