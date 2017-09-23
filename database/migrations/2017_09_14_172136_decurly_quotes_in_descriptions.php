<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Markdown;

class DecurlyQuotesInDescriptions extends Migration
{
    public $tablesToConvert = [
        'books',
        'playlists',
        'talks',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->tablesToConvert as $tableName) {
            DB::table($tableName)->get()->each(function($row) use ($tableName) {
                $en = $row->description_en;
                $th = $row->description_th;
                $newEn = $this->fixQuotes($en);
                $newTh = $this->fixQuotes($th);
                if ($newEn !== $en || $newTh !== $th) {
                    echo("Updating $tableName {$row->id}\n");
                    DB::table($tableName)
                            ->where('id', $row->id)
                            ->update([
                        'description_en' => $newEn,
                        'description_th' => $newTh,
                    ]);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No going back...
    }

    protected function fixQuotes($markdown)
    {
        if ($markdown !== null) {
            $markdown = Markdown::cleanChars($markdown);
        }
        return $markdown;
    }
}
