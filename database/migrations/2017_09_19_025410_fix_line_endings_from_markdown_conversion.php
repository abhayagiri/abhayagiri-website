<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixLineEndingsFromMarkdownConversion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->fixTable('books', ['description_en', 'description_th']);
        $this->fixTable('news', ['body_en', 'body_th']);
        $this->fixTable('playlists', ['description_en', 'description_th']);
        $this->fixTable('reflections', ['body']);
        $this->fixTable('subpages', ['body_en', 'body_th']);
        $this->fixTable('talks', ['description_en', 'description_th']);
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

    protected function nle($text) {
        $text = preg_replace('/\R/u', "\r\n", $text);
        return $text ? $text : null;
    }

    protected function fixTable($table, $columns) {
        $updated = DB::table($table)->get()->map(function ($row) use ($table, $columns) {
            $update = [];
            foreach ($columns as $column) {
                $newText = $this->nle($row->$column);
                if ($newText !== $row->$column) {
                    $update[$column] = $newText;
                }
            }
            if ($update) {
                DB::table($table)->where('id', $row->id)->update($update);
                return $row->id;
            }
        });
        dump('Updated ' . $table . ' ' . implode(',', $updated->toArray()));
    }
}
