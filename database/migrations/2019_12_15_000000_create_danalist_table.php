<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDanalistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (app()->env === 'production') return; // SKIP ON PRODUCTION
        Schema::create('danalist', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('title');
            $table->string('link');
            $table->string('short_link');
            $table->string('summary_en')->nullable();
            $table->string('summary_th')->nullable();
            $table->boolean('check_translation')->default(1);
            $table->boolean('listed')->default(1);
            $table->dateTime('last_listed_at');
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
        Schema::drop('danalist');
    }
}
