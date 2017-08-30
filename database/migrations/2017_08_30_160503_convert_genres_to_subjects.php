<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ConvertGenresToSubjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->dropForeign(['genre_id']);
        });

        Schema::rename('genres', 'subject_groups');

        Schema::table('subject_groups', function (Blueprint $table) {
            $table->text('description_en')->nullable()->after('title_th');
            $table->text('description_th')->nullable()->after('description_en');
        });

        Schema::create('subjects', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('group_id');
            $table->string('slug')->unique();
            $table->string('title_en');
            $table->string('title_th')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_th')->nullable();
            $table->boolean('check_translation')->default(false);
            $table->string('image_path')->nullable();
            $table->unsignedInteger('rank')->default(0);
            $table->timestamps();
            $table->foreign('group_id')->references('id')
                ->on('subject_groups')->onUpdate('CASCADE')->onDelete('CASCADE');
        });

        Schema::create('subject_tag', function (Blueprint $table) {
            $table->unsignedInteger('subject_id');
            $table->unsignedInteger('tag_id');
            $table->timestamps();
            $table->foreign('subject_id')->references('id')
                ->on('subjects')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('tag_id')->references('id')
                ->on('tags')->onUpdate('CASCADE')->onDelete('CASCADE');
        });

        DB::table('tags')
                ->get()
                ->each(function($row, $key) {
            DB::table('subjects')->insert([
                'id' => $row->id,
                'group_id' => $row->genre_id,
                'slug' => $row->slug,
                'title_en' => $row->title_en,
                'title_th' => $row->title_th,
                'check_translation' => $row->check_translation,
                'image_path' => $row->image_path,
                'created_at' => $row->created_at,
                'updated_at' => $row->updated_at,
            ]);
            DB::table('subject_tag')
                ->insert([
                    'subject_id' => $row->id,
                    'tag_id' => $row->id,
                ]);
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->dropColumn('genre_id');
            $table->dropColumn('image_path');
            $table->dropColumn('rank');
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
            $table->unsignedInteger('genre_id')->after('id');
            $table->string('image_path')->nullable()->after('check_translation');
            $table->unsignedInteger('rank')->default(0)->after('image_path');
        });

        DB::table('subjects')
                ->get()
                ->each(function($row, $key) {
            DB::table('tags')
                ->where('id', $row->id)
                ->update(['genre_id' => $row->group_id]);
        });

        Schema::drop('subject_tag');

        Schema::drop('subjects');

        Schema::table('subject_groups', function (Blueprint $table) {
            $table->dropColumn('description_th');
            $table->dropColumn('description_en');
        });

        Schema::rename('subject_groups', 'genres');

        Schema::table('tags', function (Blueprint $table) {
            $table->foreign('genre_id')->references('id')
                ->on('genres')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }
}
