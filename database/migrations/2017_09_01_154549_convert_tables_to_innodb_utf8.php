<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ConvertTablesToInnodbUtf8 extends Migration
{
    public $innoDBTablesToConvert = [
        'authors',
        'talks',
    ];

    public $tablesToConvert = [
        'backup',
        'books',
        'columns',
        'construction',
        'danalist',
        'dropdowns',
        'faq',
        'logs',
        'mahaguild',
        'messages',
        'misc',
        'news',
        'options',
        'pages',
        'reflections',
        'request',
        'residents',
        'rideshare',
        'schedule',
        'settings',
        'subpages',
        'tasks',
        'uploads',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->innoDBTablesToConvert as $table) {
            DB::statement('ALTER TABLE `' . $table . '` ' .
                'CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci');
        }

        foreach ($this->tablesToConvert as $table) {
            DB::statement('ALTER TABLE `' . $table . '` ' .
                'ENGINE=InnoDB');
            DB::statement('ALTER TABLE `' . $table . '` ' .
                'CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach ($this->tablesToConvert as $table) {
            DB::statement('ALTER TABLE `' . $table . '` ' .
                'CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci');
            DB::statement('ALTER TABLE `' . $table . '` ' .
                'ENGINE=MyISAM');
        }

        foreach ($this->innoDBTablesToConvert as $table) {
            DB::statement('ALTER TABLE `' . $table . '` ' .
                'CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci');
        }
    }
}
