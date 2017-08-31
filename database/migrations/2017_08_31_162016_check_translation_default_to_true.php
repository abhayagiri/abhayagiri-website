<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CheckTranslationDefaultToTrue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->boolean('check_translation')->default(true)->change();
        });
        Schema::table('subject_groups', function (Blueprint $table) {
            $table->boolean('check_translation')->default(true)->change();
        });
        Schema::table('tags', function (Blueprint $table) {
            $table->boolean('check_translation')->default(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->boolean('check_translation')->default(false)->change();
        });
        Schema::table('subject_groups', function (Blueprint $table) {
            $table->boolean('check_translation')->default(false)->change();
        });
        Schema::table('authors', function (Blueprint $table) {
            $table->boolean('check_translation')->default(false)->change();
        });
    }
}
