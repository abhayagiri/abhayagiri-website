<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (app()->env === 'production') return; // SKIP ON PRODUCTION
        Schema::create('contact_options', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name_en');
            $table->string('name_th')->nullable();
            $table->string('slug');
            $table->mediumtext('body_en');
            $table->mediumtext('body_th')->nullable();
            $table->mediumtext('confirmation_en');
            $table->mediumtext('confirmation_th')->nullable();
            $table->string('email');
            $table->boolean('active')->default(0);
            $table->boolean('published')->default(0);
            $table->integer('rank')->unsigned()->default(0);
            $table->timestamps();
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
        Schema::drop('contact_options');
    }
}
