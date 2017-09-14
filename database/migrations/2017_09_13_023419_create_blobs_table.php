<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Markdown;

class CreateBlobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blobs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key');
            $table->string('name');
            $table->text('body_en')->nullable();
            $table->text('body_th')->nullable();
            $table->boolean('check_translation')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        $now = Carbon::now();
        $getMisc = function($urlTitle) {
            $body = DB::table('misc')
                ->where('url_title', $urlTitle)->value('body');
            return Markdown::fromHtml($body);
        };

        DB::table('blobs')->insert([
            [
                'key' => 'contact.form',
                'name' => 'Contact Form Information',
                'body_en' => $getMisc('contact'),
                'body_th' => $getMisc('contact-thai'),
                'check_translation' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'books.request.form',
                'name' => 'Book Request Form Information',
                'body_en' => $getMisc('book-request'),
                'body_th' => $getMisc('book-request-thai'),
                'check_translation' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('pages')
            ->where('title', '=', 'misc')
            ->update(['mahapanel' => 'no']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('blobs');

        DB::table('pages')
            ->where('title', '=', 'misc')
            ->update(['mahapanel' => 'yes']);
    }
}
