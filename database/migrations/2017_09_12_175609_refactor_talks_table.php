<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Language;
use App\Markdown;
use App\Util;

class RefactorTalksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('talks', function (Blueprint $table) {
            $table->renameColumn('title', 'title_en');
            $table->renameColumn('body', 'description_en');
            $table->renameColumn('date', 'posted_at');
            $table->renameColumn('url_title', 'slug');
            $table->renameColumn('mp3', 'media_path');
            $table->renameColumn('recording_date', 'recorded_on');
            $table->unsignedInteger('language_id')->after('id');
            $table->string('image_path')->nullable()->after('check_translation');
            $table->boolean('draft')->default(false)->after('hide_from_latest');
            $table->datetime('created_at')->nullable();
            $table->datetime('updated_at')->nullable();
            $table->datetime('deleted_at')->nullable();
        });

        Schema::table('talks', function (Blueprint $table) {
            $table->string('slug', 255)->change();
            $table->string('title_en', 255)->change();
            $table->string('media_path', 255)->change();
            $table->date('recorded_on')->change();
        });

        DB::table('talks')->get()->each(function($talk) {
            $language = Language::where('title_en', $talk->language)->first();
            if (!$language) {
                echo 'Unknown language ' . $talk->language . ' for talk '
                    . $talk->id . "\n";
                $language = Language::where('title_en', 'English')->firstOrFail();
            }
            if (!$talk->media_path) {
                $mediaPath = null;
            } else if (starts_with($talk->media_path, '../')) {
                $mediaPath = preg_replace('_^../_', '', $talk->media_path);
            } else {
                $mediaPath = 'audio/' . $talk->media_path;
            }
            DB::table('talks')
                    ->where('id', '=', $talk->id)
                    ->update([
                'language_id' => $language->id,
                'description_en' => Markdown::fromHtml($talk->description_en),
                'description_th' => Markdown::fromHtml($talk->description_th),
                'media_path' => $mediaPath,
                'draft' => strtolower($talk->status) === 'draft',
                'deleted_at' => strtolower($talk->status) === 'closed'
                    ? Carbon::now() : null,
            ]);
        });

        Schema::table('talks', function (Blueprint $table) {
            $table->dropColumn('language');
            $table->dropColumn('status');
            $table->dropColumn('user');
            $table->foreign('language_id')->references('id')
                ->on('languages')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('talks', function (Blueprint $table) {
            $table->renameColumn('title_en', 'title');
            $table->renameColumn('description_en', 'body');
            $table->renameColumn('posted_at', 'date');
            $table->renameColumn('slug', 'url_title');
            $table->renameColumn('media_path', 'mp3');
            $table->renameColumn('recorded_on', 'recording_date');
            $table->string('language', 100);
            $table->string('status', 25)->default('open');
            $table->integer('user')->length(5)->nullable();
        });

        Schema::table('talks', function (Blueprint $table) {
            $table->string('url_title', 100)->change();
            $table->string('title', 100)->change();
            $table->string('mp3', 300)->change();
            $table->datetime('recording_date')->change();
        });

        DB::table('talks')->get()->each(function($talk) {
            $language = Language::findOrFail($talk->language_id);
            if (!$talk->mp3) {
                $mp3 = null;
            } else if (starts_with($talk->mp3, 'audio/')) {
                $mp3 = preg_replace('_^audio/_', '', $talk->mp3);
            } else {
                $mp3 = '../' . $talk->mp3;
            }
            DB::table('talks')
                    ->where('id', '=', $talk->id)
                    ->update([
                'language' => $language->title_en,
                'mp3' => $mp3,
                'status' => $talk->deleted_at ? 'closed' :
                    ($talk->draft ? 'draft' : 'open'),
            ]);
        });

        Schema::table('talks', function (Blueprint $table) {
            $table->dropForeign(['language_id']);
            $table->dropColumn('language_id');
            $table->dropColumn('image_path');
            $table->dropColumn('draft');
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
            $table->dropColumn('deleted_at');
        });
    }
}
