<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSyncTaskTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("sync_tasks", function (Blueprint $table) {
            $table->increments('id');
            $table->string('key')->collation('utf8mb4_bin')->unique();
            // JSON column on MySQL 5.7 can't have a default value.
            // ERROR: Syntax error or access violation: 1101
            $table->json('extra')->nullable();
            $table->datetime('started_at')->nullable();
            $table->datetime('completed_at')->nullable();
            $table->datetime('locked_until')->nullable();
            $table->timestamps();
        });

        Schema::create("sync_task_logs", function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sync_task_id');
            // TEXT column on MySQL 5.7 can't have a default value.
            // ERROR: Syntax error or access violation: 1101
            $table->text('log');
            $table->timestamps(6); // microsecond support
            $table->foreign('sync_task_id')->references('id')->on('sync_tasks')
                  ->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sync_task_logs');
        Schema::dropIfExists('sync_tasks');
    }
}
