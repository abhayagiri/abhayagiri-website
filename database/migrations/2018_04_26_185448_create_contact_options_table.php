<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_options', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name_en');
            $table->string('name_th')->nullable();
            $table->string('slug');
            $table->text('body_en');
            $table->text('body_th')->nullable();
            $table->string('email');
            $table->boolean('active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contact_options');
    }
}
