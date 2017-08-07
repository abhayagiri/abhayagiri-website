<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSlugImageTimestampsToAuthors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `authors` ENGINE=InnoDB;');

        Schema::table('authors', function (Blueprint $table) {
            $table->increments('id')->change();
            $table->string('title_th')->nullable()->after('title');
            $table->boolean('check_translation')->default(false)->after('title_th');
            $table->string('image_path')->nullable()->after('check_translation');
            $table->timestamps();
        });

        DB::table('authors')->get()->each(function($author, $key) {
            DB::table('authors')
                ->where('id', $author->id)
                ->update(['url_title' => str_slug($author->title)]);
        });

        // Delete duplicates
        DB::table('authors')
            ->select(DB::raw('url_title, COUNT(*) AS dup_count'))
            ->groupBy('url_title')
            ->havingRaw('dup_count > 1')
            ->get()->each(function($row, $key) {
                $url_title = $row->url_title;
                $n = $row->dup_count;
                DB::table('authors')
                    ->where('url_title', $url_title)
                    ->limit($n - 1)
                    ->delete();
            });

        Schema::table('authors', function (Blueprint $table) {
            $table->string('url_title')->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->string('url_title')->nullable()->change();
            $table->dropColumn('title_th');
            $table->dropColumn('check_translation');
            $table->dropColumn('image_path');
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });

        DB::statement('ALTER TABLE `authors` ENGINE=MyISAM;');
    }
}
