<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (app()->env === 'production') return; // SKIP ON PRODUCTION
        Schema::create('subjects', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('group_id')->unsigned();
            $table->string('slug')->unique();
            $table->string('title_en');
            $table->string('title_th')->nullable();
            $table->mediumtext('description_en')->nullable();
            $table->mediumtext('description_th')->nullable();
            $table->boolean('check_translation')->default(1);
            $table->string('image_path')->nullable();
            $table->integer('rank')->unsigned()->default(0);
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
        Schema::drop('subjects');
    }
}
