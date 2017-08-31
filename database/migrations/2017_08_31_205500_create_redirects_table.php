<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRedirectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('redirects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('from')->unique();
            $table->text('to');
            $table->timestamps();
        });

        DB::table('talks')->get()
                ->each(function($talk, $key) {
            DB::table('redirects')->insert([
                'from' => 'audio/' . $talk->url_title,
                'to' => json_encode([
                    'type' => 'talks',
                    'lng' => 'en',
                    'id' => $talk->id,
                ]),
            ]);
            DB::table('redirects')->insert([
                'from' => 'th/audio/' . $talk->url_title,
                'to' => json_encode([
                    'type' => 'talks',
                    'lng' => 'th',
                    'id' => $talk->id,
                ]),
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('redirects');
    }
}
