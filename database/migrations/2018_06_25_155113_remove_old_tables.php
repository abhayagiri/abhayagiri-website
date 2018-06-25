<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveOldTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('backup');
        Schema::dropIfExists('columns');
        Schema::dropIfExists('construction');
        Schema::dropIfExists('dropdowns');
        Schema::dropIfExists('faq');
        Schema::dropIfExists('logs');
        Schema::dropIfExists('mahaguild');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('misc');
        Schema::dropIfExists('old_books');
        Schema::dropIfExists('old_danalist');
        Schema::dropIfExists('old_news');
        Schema::dropIfExists('old_reflections');
        Schema::dropIfExists('old_residents');
        Schema::dropIfExists('old_settings');
        Schema::dropIfExists('old_subpages');
        Schema::dropIfExists('options');
        Schema::dropIfExists('request');
        Schema::dropIfExists('rideshare');
        Schema::dropIfExists('schedule');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('uploads');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No going back!
    }
}
