<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTravelingCommunityMembers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('residents', function (Blueprint $table) {
            $table
                ->string('resident_status')
                ->default('Current');
        });
        DB::table('columns')->insert([
            'parent' => 52,
            'display_title' => 'Resident Status',
            'title' => 'resident_status',
            'column_type' => 'dropdown',
            'upload_directory' => '',
            'date' => '2013-03-17 00:00:00',
            'display' => 'yes',
            'user' => 0,
            'status' => 'Open',
        ]);
        $dropdownId = DB::table('dropdowns')->insertGetId([
            'title' => 'resident_status',
            'date' => '2013-03-17 00:00:00',
            'source' => 0,
            'user' => 0,
            'status' => 'Open',
        ]);
        DB::table('options')->insert([
            [
                'parent' => $dropdownId,
                'title' => 'Current',
                'value' => 'Current',
                'date' => '2017-01-01 00:01:00',
                'user' => 0,
                'status' => 'Open',
            ],
            [
                'parent' => $dropdownId,
                'title' => 'Traveling',
                'value' => 'Traveling',
                'date' => '2017-01-01 00:00:40',
                'user' => 0,
                'status' => 'Open',
            ],
            [
                'parent' => $dropdownId,
                'title' => 'Former',
                'value' => 'Former',
                'date' => '2017-01-01 00:00:20',
                'user' => 0,
                'status' => 'Open',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $dropdownId = DB::table('dropdowns')
            ->where('title', '=', 'resident_status')
            ->value('id');
        DB::table('options')
            ->where('parent', '=', $dropdownId)
            ->delete();
        DB::table('dropdowns')
            ->where('id', '=', $dropdownId)
            ->delete();
        DB::table('columns')
            ->where('parent', '=', 52)
            ->where('title', '=', 'resident_status')
            ->delete();
    }
}
