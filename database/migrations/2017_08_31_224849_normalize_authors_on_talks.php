<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NormalizeAuthorsOnTalks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('talks', function (Blueprint $table) {
            $table->unsignedInteger('author_id')->after('title_th');
        });

        DB::table('talks')
                ->join('authors', 'talks.author', '=', 'authors.title')
                ->update(['author_id' => DB::raw('authors.id')]);

        Schema::table('talks', function (Blueprint $table) {
            $table->dropColumn('author');
            $table->foreign('author_id')->references('id')
                ->on('authors')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('talks', function (Blueprint $table) {
            $table->string('author', 100)->after('title');
        });

        DB::table('talks')
                ->join('authors', 'talks.author_id', '=', 'authors.id')
                ->update(['author' => DB::raw('authors.title')]);

        Schema::table('talks', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            $table->dropColumn('author_id');
        });
    }
}
