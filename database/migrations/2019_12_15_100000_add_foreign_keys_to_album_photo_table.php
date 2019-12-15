<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToAlbumPhotoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (app()->env === 'production') return; // SKIP ON PRODUCTION
        Schema::table('album_photo', function(Blueprint $table)
        {
            $table->foreign('album_id')->references('id')->on('albums')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('photo_id')->references('id')->on('photos')->onUpdate('CASCADE')->onDelete('CASCADE');
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
        Schema::table('album_photo', function(Blueprint $table)
        {
            $table->dropForeign('album_photo_album_id_foreign');
            $table->dropForeign('album_photo_photo_id_foreign');
        });
    }
}
