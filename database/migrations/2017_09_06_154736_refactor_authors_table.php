<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RefactorAuthorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->renameColumn('title', 'title_en');
            $table->renameColumn('url_title', 'slug');
            $table->dropColumn('user');
            $table->dropColumn('date');
            $table->dropColumn('status');
        });

        DB::statement('ALTER TABLE `authors` MODIFY COLUMN `slug` ' .
            'varchar(255) AFTER `id`');

        Schema::table('authors', function (Blueprint $table) {
            $table->string('title_en', 255)->nullable(false)->change();
        });

        DB::table('authors')->update([
            'created_at' => DB::raw('updated_at')
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->renameColumn('title_en', 'title');
            $table->renameColumn('slug', 'url_title');
        });

        Schema::table('authors', function (Blueprint $table) {
            $table->string('title', 300)->nullable(true)->change();
            $table->integer('user', 5)->nullable()->before('created_at');
            $table->datetime('date')->nullable()->before('created_at');
            $table->string('status', 300)->nullable()->before('created_at');
        });

        DB::statement('ALTER TABLE `authors` MODIFY COLUMN `url_title` ' .
            'varchar(255) AFTER `image_path`');
    }
}
