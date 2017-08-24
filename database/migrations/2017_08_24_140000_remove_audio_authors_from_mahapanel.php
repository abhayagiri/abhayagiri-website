<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveAudioAuthorsFromMahapanel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('pages')
            ->where('title', 'Audio')
            ->orWhere('title', 'Authors')
            ->update(['mahapanel' => 'no']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('pages')
            ->where('title', 'Audio')
            ->orWhere('title', 'Authors')
            ->update(['mahapanel' => 'yes']);
    }
}
