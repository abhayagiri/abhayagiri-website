<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTalksToPages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('pages')->insert([
            'title' => 'Talks',
            'thai_title' => 'TODO',
            'url_title' => 'talks',
            'body' => '',
            'status' => 'Open',
            'icon' => 'icon-volume-up',
            'date' => Carbon::now(),
            'class' => 'medianav',
            'www' => 'yes',
            'mahapanel' => 'no',
            'meta_description' => 'Dhamma talks, chanting, and teachings from the monks at Abhayagiri.',
            'user' => 0,
            'page_type' => 'Table',
            'display_type' => 'Table',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('pages')
            ->where('title', '=', 'Talks')
            ->delete();
    }
}
