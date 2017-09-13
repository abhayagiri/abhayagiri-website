<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDraftToPlaylists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('playlists', function(Blueprint $table) {
            $table->boolean('draft')->after('published_at');
            $table->dropColumn('status');
        });

        Schema::table('playlists', function(Blueprint $table) {
            $table->renameColumn('published_at', 'posted_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('playlists', function(Blueprint $table) {
            $table->renameColumn('posted_at', 'published_at');
            $table->dropColumn('draft');
            $table->enum('status', ['open', 'closed'])->default('open');
        });
    }
}
