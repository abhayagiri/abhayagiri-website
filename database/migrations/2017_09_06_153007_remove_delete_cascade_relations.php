<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveDeleteCascadeRelations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
        });
        Schema::table('subjects', function (Blueprint $table) {
            $table->foreign('group_id')->references('id')
                ->on('subject_groups')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
        Schema::table('talks', function (Blueprint $table) {
            $table->dropForeign(['type_id']);
        });
        Schema::table('talks', function (Blueprint $table) {
            $table->foreign('type_id')->references('id')
                ->on('talk_types')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
        });
        Schema::table('subjects', function (Blueprint $table) {
            $table->foreign('group_id')->references('id')
                ->on('subject_groups')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
        Schema::table('talks', function (Blueprint $table) {
            $table->dropForeign(['type_id']);
        });
        Schema::table('talks', function (Blueprint $table) {
            $table->foreign('type_id')->references('id')
                ->on('talk_types')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }
}
