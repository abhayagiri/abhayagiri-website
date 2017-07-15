<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class ConvertAudioToInnodb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(
            'ALTER TABLE `audio` ENGINE=InnoDB;'
        );
        DB::connection()->getPdo()->exec(
            'ALTER TABLE `audio` MODIFY `id` int(10) unsigned NOT NULL;'
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::connection()->getPdo()->exec(
            'ALTER TABLE `audio` MODIFY `id` int(5) NOT NULL;'
        );
        DB::connection()->getPdo()->exec(
            'ALTER TABLE `audio` ENGINE=MyISAM;'
        );
    }
}
