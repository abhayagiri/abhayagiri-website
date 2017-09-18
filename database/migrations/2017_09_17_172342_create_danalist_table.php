<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDanalistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('danalist', 'old_danalist');
        Schema::create('danalist', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('link');
            $table->string('short_link');
            $table->string('summary_en')->nullable();
            $table->string('summary_th')->nullable();
            $table->boolean('check_translation')->default(true);
            $table->boolean('listed')->default(true);
            $table->datetime('last_listed_at');
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
        Schema::drop('danalist');
        Schema::rename('old_danalist', 'danalist');
    }
}
