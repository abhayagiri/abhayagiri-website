<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('settings', 'old_settings');

        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key');
            $table->string('name');
            $table->string('description')->nullable();
            $table->text('value')->nullable();
            $table->text('field');
            $table->tinyInteger('active');
            $table->timestamps();
        });

        DB::table('settings')->insert([
            'key' => 'home_news_count',
            'name' => 'Home news article count',
            'description' => 'Number of news articles to show on the home page',
            'value' => DB::table('old_settings')
                ->where('key_', 'home.news.count')->value('value'),
            'field' => '{"name": "value", "label": "Value", "type": "number"}',
            'active' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('settings');
        Schema::rename('old_settings', 'settings');
    }
}
