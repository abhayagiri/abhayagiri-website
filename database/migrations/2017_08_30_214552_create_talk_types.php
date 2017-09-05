<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTalkTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('talk_types', function (Blueprint $table) {
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

        Schema::table('talks', function (Blueprint $table) {
            $table->unsignedInteger('type_id')->after('id');
        });

        $mapping = [];
        DB::table('talks')
                ->distinct()->select('category')->get()
                ->each(function($row, $key) use (&$mapping) {
            $now = new \DateTime();
            $id = DB::table('talk_types')->insertGetId([
                'slug' => str_slug($row->category),
                'title_en' => $row->category,
                'check_translation' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $mapping[$row->category] = $id;
        });

        DB::table('talks')
                ->get()
                ->each(function($row, $key) use ($mapping) {
            DB::table('talks')
                ->where('id', $row->id)
                ->update(['type_id' => $mapping[$row->category]]);
        });

        Schema::table('talks', function (Blueprint $table) {
            $table->foreign('type_id')->references('id')
                ->on('talk_types')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->dropColumn('category');
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
            $table->string('category', 100)->nullable();
            $table->dropForeign(['type_id']);
        });

        $mapping = [];
        DB::table('talk_types')
                ->each(function($row, $key) use (&$mapping) {
            $mapping[$row->id] = $row->title_en;
        });

        DB::table('talks')
                ->get()
                ->each(function($row, $key) use ($mapping) {
            DB::table('talks')
                ->where('id', $row->id)
                ->update(['category' => $mapping[$row->type_id]]);
        });

        Schema::table('talks', function (Blueprint $table) {
            $table->dropColumn('type_id');
        });

        Schema::drop('talk_types');
    }
}
