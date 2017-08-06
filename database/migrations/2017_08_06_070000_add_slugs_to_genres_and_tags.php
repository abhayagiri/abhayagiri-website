<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSlugsToGenresAndTags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('genres', function (Blueprint $table) {
            $table->string('slug')->unique()->after('id');
        });
        Schema::table('tags', function (Blueprint $table) {
            $table->string('slug')->unique()->after('genre_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('genres', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
        Schema::table('tags', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
}

