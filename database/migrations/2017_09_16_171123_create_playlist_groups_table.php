<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlaylistGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('playlist_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug');
            $table->string('title_en');
            $table->string('title_th')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_th')->nullable();
            $table->boolean('check_translation')->default(true);
            $table->string('image_path')->nullable();
            $table->unsignedInteger('rank')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('playlists', function (Blueprint $table) {
            $table->unsignedInteger('group_id')->nullable()->after('id');
            $table->foreign('group_id')->references('id')
                ->on('playlist_groups')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('playlists', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
            $table->dropColumn('group_id');
        });

        Schema::drop('playlist_groups');
    }
}
