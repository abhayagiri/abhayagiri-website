<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddThaiToTalks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('talks', function (Blueprint $table) {
            $table->string('title_th')->nullable()->after('title');
            $table->text('body')->nullable()->change();
            $table->text('description_th')->nullable()->after('body');
            $table->boolean('check_translation')->default(true)->after('description_th');
        });

        // http://php.net/manual/en/function.urldecode.php#79595
        $utf8_urldecode = function($str) {
            $str = preg_replace("/%u([0-9a-f]{3,4})/i", "&#x\\1;", urldecode($str));
            return html_entity_decode($str, null, 'UTF-8');;
        };

        DB::table('talks')->get()
                ->each(function($talk, $key) use ($utf8_urldecode) {
            $update = [];
            $slug = str_slug($talk->title);
            if (preg_match('/^[-0-9]*$/', $slug)) {
                // Assume to be Thai
                $slug = str_slug($utf8_urldecode($talk->url_title));
                if (preg_match('/^[-0-9]*$/', $slug) || $slug === 'u0' || $slug === 'u0e') {
                    $title = 'Unknown Thai Talk';
                } else {
                    $title = ucwords(preg_replace('/-/', ' ', $slug));
                }
                DB::table('talks')->where('id', $talk->id)->update([
                    'title' => $title,
                    'title_th' => $talk->title,
                    'body' => null,
                    'description_th' => $talk->body,
                ]);
            }
        });
        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('talks')
            ->whereNotNull('title_th')
            ->update([
                'title' => DB::raw('title_th'),
                'body' => DB::raw('description_th'),
            ]);

        Schema::table('talks', function (Blueprint $table) {
            $table->mediumText('body')->nullable(false)->change();
            $table->dropColumn('title_th');
            $table->dropColumn('description_th');
            $table->dropColumn('check_translation');
        });
        //
    }
}
