<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeletedAtToTables extends Migration
{
    public $tablesToConvert = [
        'authors',
        'playlists',
        'subject_groups',
        'subjects',
        'tags',
        'talk_types',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->tablesToConvert as $tableName) {
            Schema::table($tableName, function(Blueprint $table) {
                $table->datetime('deleted_at')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach ($this->tablesToConvert as $tableName) {
            Schema::table($tableName, function(Blueprint $table) {
                $table->dropColumn('delete_at');
            });
        }
    }
}
