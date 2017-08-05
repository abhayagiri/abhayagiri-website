<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAutoincrementToAudioId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
	    DB::statement('ALTER TABLE `audio` CHANGE id id int(10) unsigned NOT NULL AUTO_INCREMENT');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
	    DB::statement('ALTER TABLE `audio` CHANGE id id int(10) unsigned NOT NULL');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}

