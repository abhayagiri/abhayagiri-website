<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubpagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('subpages', 'old_subpages');
        Schema::create('subpages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('page');
            $table->string('subpath');
            $table->string('title_en');
            $table->string('title_th')->nullable();
            $table->text('body_en')->nullable();
            $table->text('body_th')->nullable();
            $table->boolean('check_translation')->default(true);
            $table->unsignedInteger('rank')->default(0);
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
        Schema::drop('subpages');
        Schema::rename('old_subpages', 'subpages');
    }
}
