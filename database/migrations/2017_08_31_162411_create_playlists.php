<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use League\HTMLToMarkdown\HtmlConverter;

class CreatePlaylists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('playlists', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->unique();
            $table->string('title_en');
            $table->string('title_th')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_th')->nullable();
            $table->boolean('check_translation')->default(true);
            $table->string('image_path')->nullable();
            $table->unsignedInteger('rank')->default(0);
            $table->timestamps();
        });

        Schema::create('playlist_talk', function (Blueprint $table) {
            $table->unsignedInteger('playlist_id');
            $table->unsignedInteger('talk_id');
            $table->timestamps();
            $table->foreign('playlist_id')->references('id')
                ->on('playlists')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('talk_id')->references('id')
                ->on('talks')->onUpdate('CASCADE')->onDelete('CASCADE');
        });

        $collectionTypeId = DB::table('talk_types')
            ->where('slug', 'collections')
            ->value('id');

        $converter = new HtmlConverter();
        $convert = function($html) use ($converter) {
            $markdown = $converter->convert($html);
            $markdown = preg_replace('/https?:\/\/(www\.)?abhayagiri\.org/', '', $markdown);
            return $markdown;
        };

        DB::table('talks')
                ->where('type_id', $collectionTypeId)
                ->get()->each(function($talk, $key) use($convert) {
            DB::table('playlists')->insert([
                'slug' => str_slug($talk->title),
                'title_en' => $talk->title,
                'description_en' => $convert($talk->body),
                'check_translation' => true,
                'rank' => 10,
                'updated_at' => Carbon::now(),
                'created_at' => new Carbon($talk->date),
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('playlist_talk');

        Schema::drop('playlists');
    }
}
