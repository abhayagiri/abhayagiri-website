<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RemoveEnumOnResidents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('residents', function (Blueprint $table) {
            $sql = <<<SQL
                ALTER TABLE `residents` MODIFY `status`
                    varchar(255)
                    COLLATE utf8mb4_unicode_ci
                    NOT NULL DEFAULT 'current';
SQL;
            DB::statement($sql);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('residents', function (Blueprint $table) {
            $sql = <<<SQL
                ALTER TABLE `residents` MODIFY `status`
                    enum('current','traveling','former')
                    COLLATE utf8mb4_unicode_ci
                    NOT NULL DEFAULT 'current';
SQL;
            DB::statement($sql);
        });
    }
}
