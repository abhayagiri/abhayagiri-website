<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CleanupBadData20170519 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('columns')->where('id', '=', 1642)->delete();
        DB::table('columns')->where('id', '=', 6061)->delete();
        DB::table('columns')->where('id', '=', 53)->delete();
        DB::table('columns')->where('id', '=', 54)->delete();
        DB::table('columns')->where('id', '=', 55)->delete();
        DB::table('residents')->where('id', '=', 3)->update([
            'date' => '1998-01-01 00:00:00',
        ]);
        DB::table('residents')->where('id', '=', 9)->update([
            'date' => '1992-01-01 00:00:00',
        ]);
        DB::table('residents')->where('id', '=', 13)->update([
            'date' => '1988-01-01 00:00:00',
        ]);
        DB::table('residents')->where('id', '=', 14)->update([
            'date' => '1987-01-01 00:00:00',
        ]);
        DB::table('residents')->where('id', '=', 60)->update([
            'date' => '1980-01-01 00:00:00',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No going back.
    }
}
