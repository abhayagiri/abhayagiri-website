<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubpagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (app()->env === 'production') return; // SKIP ON PRODUCTION
        Schema::create('subpages', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('page');
            $table->string('subpath');
            $table->string('title_en');
            $table->string('title_th')->nullable();
            $table->mediumtext('body_en')->nullable();
            $table->mediumtext('body_th')->nullable();
            $table->boolean('check_translation')->default(1);
            $table->integer('rank')->unsigned()->default(0);
            $table->boolean('draft')->default(0);
            $table->dateTime('posted_at');
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
        Schema::drop('subpages');
    }
}
