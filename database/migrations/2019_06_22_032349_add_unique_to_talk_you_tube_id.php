<?php

use App\Util;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUniqueToTalkYouTubeId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $duplicates = Util::getTableDuplicates('talks', 'youtube_id');
        if ($duplicates->isNotEmpty()) {
            throw new \Exception("Found non-unique youtube_ids in talks tables\n" .
                $duplicates->map(function($row) {
                    return "{$row->id}: {$row->youtube_id}";
                })->join("\n"));
        }
        Schema::table('talks', function (Blueprint $table) {
            $table->string('youtube_id')->collation('utf8mb4_bin')->change();
            $table->unique('youtube_id');
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
            // dropUnique doesn't seem to pick up the index naming scheme....
            $table->dropUnique('talks_youtube_id_unique');
            $table->string('youtube_id')->collation('utf8mb4_unicode_ci')->change();
        });
    }
}
