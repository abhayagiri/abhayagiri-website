<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ConvertBookBlobsToSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $blob = DB::table('blobs')
            ->where('key', 'books.request.form')->first();
        $now = new \DateTime();
        DB::table('settings')->insert([
            [
                'key' => 'books.request_form_en',
                'value' => $blob->body_en,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'books.request_form_th',
                'value' => $blob->body_th,
                'created_at' => $now,
                'updated_at' => $now,
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
        DB::table('settings')
            ->where('key', 'books.request_from_en')
            ->orWhere('key', 'books.request_form_th')
            ->delete();
    }
}
