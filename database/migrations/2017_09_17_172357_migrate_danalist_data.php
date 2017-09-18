<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Markdown;
use App\Models\Danalist;
use App\Util;

class MigrateDanalistData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('old_danalist')->get()
                ->each(function($danalist) {

            $link = $danalist->link;
            // Only do this once on production
            if (config('app.env') === 'production') {
                $longLink = $this->resolveUrl($link);
                if (!$longLink) {
                    echo 'Could not resolve ' . $danalist->link . ' from ' . $danalist->id;
                    $link = $longLink = 'https://www.abhayagiri.org/';
                }
                if ($link === $longLink) {
                    $shortLink = null;
                } else {
                    $shortLink = $link;
                }
                dump([$link, $longLink, $shortLink]);
            } else {
                $longLink = $link;
                $shortLink = $link;
            }

            $date = Util::createDateTimeFromPacificDb($danalist->date);

            $status = strtolower($danalist->status);
            if ($status == 'draft') {
                $listed = false;
            } else if ($status == 'closed') {
                $listed = false;
            } else {
                $listed = true;
            }

            Danalist::forceCreate([
                'id' => $danalist->id,
                'title' => $danalist->title,
                'link' => $longLink,
                'short_link' => $shortLink,
                'summary_en' => Markdown::fromHtml($danalist->body),
                'summary_th' => null,
                'check_translation' => true,
                'listed' => $listed,
                'last_listed_at' => $listed ? Carbon::now() : $date,
                'created_at' => $date,
                'updated_at' => Carbon::now(),
                'deleted_at' => null,
            ]);

        });

        DB::table('pages')
            ->where('title', '=', 'Danalist')
            ->update(['mahapanel' => 'no']);    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('pages')
            ->where('title', '=', 'Danalist')
            ->update(['mahapanel' => 'yes']);

        DB::table('danalist')->delete();
    }

    // courtesy of http://www.beliefmedia.com/resolve-short-url
    protected function resolveUrl($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $headers = curl_exec($ch);
        $redirectUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        curl_close($ch);
        return $redirectUrl;
    }
}
