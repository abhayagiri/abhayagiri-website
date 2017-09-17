<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

use App\Models\Language;

class CreateLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->unique();
            $table->string('title_en');
            $table->string('title_th');
            $table->softDeletes();
            $table->timestamps();
        });

        Language::forceCreate([
            'id' => 1,
            'code' => 'en',
            'title_en' => 'English',
            'title_th' => 'อังกฤษ',
        ]);
        Language::forceCreate([
            'id' => 2,
            'code' => 'th',
            'title_en' => 'Thai',
            'title_th' => 'ไทย',
        ]);
        Language::forceCreate([
            'id' => 3,
            'code' => 'en+th',
            'title_en' => 'English and Thai',
            'title_th' => 'ภาษาอังกฤษและภาษาไทย',
        ]);
        Language::forceCreate([
            'id' => 4,
            'code' => 'zh',
            'title_en' => 'Chinese',
            'title_th' => 'ชาวจีน',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('languages');
    }
}
