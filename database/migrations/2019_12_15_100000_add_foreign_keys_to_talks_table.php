<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTalksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (app()->env === 'production') return; // SKIP ON PRODUCTION
        Schema::table('talks', function(Blueprint $table)
        {
            $table->foreign('author_id')->references('id')->on('authors')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('language_id')->references('id')->on('languages')->onUpdate('CASCADE')->onDelete('RESTRICT');
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
        Schema::table('talks', function(Blueprint $table)
        {
            $table->dropForeign('talks_author_id_foreign');
            $table->dropForeign('talks_language_id_foreign');
        });
    }
}
