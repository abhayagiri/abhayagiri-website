<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Migrations\Migration;

class UpdateCharacterSetCollationToUtf8mb4Utf8mb4UnicodeCi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->updateCharacterSetCollation('utf8mb4', 'utf8mb4_unicode_ci');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->updateCharacterSetCollation('utf8', 'utf8_unicode_ci');
    }

    /**
     * Update all tables' current and default character set and collation.
     *
     * Note: This is MySQL specific.
     *
     * @param  string  $characterSet
     * @param  string  $collation
     * @return void
     */
    public function updateCharacterSetCollation($characterSet, $collation)
    {
        foreach (DB::select(DB::raw("SHOW TABLES;")) as $tableObj) {
            foreach ($tableObj as $key => $table) {
                Log::notice("Converting $table to $characterSet, $collation\n");
                $alterSql = "ALTER TABLE `$table` CONVERT TO CHARACTER SET $characterSet COLLATE $collation";
                DB::statement(DB::raw($alterSql));
            }
        }
    }
}
