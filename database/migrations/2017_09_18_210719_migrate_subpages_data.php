<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Markdown;
use App\Models\Redirect;
use App\Models\Subpage;
use App\Util;

class MigrateSubpagesData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $ranks = [];

        DB::table('old_subpages')
                ->where('language', 'English')
                ->orderBy('status', 'desc')
                ->orderBy('date', 'desc')
                ->get()->each(function($subpage) use (&$thaiPages, &$ranks) {

            $page = strtolower($subpage->page);
            if (!$page) {
                $page = 'support';
            }
            if (array_key_exists($page, $ranks)) {
                $ranks[$page] += 1;
            } else {
                $ranks[$page] = 1;
            }
            $rank = $ranks[$page];

            $thaiSubpage = DB::table('old_subpages')
                ->where('url_title', $subpage->url_title . '-thai')
                ->first();
            if ($thaiSubpage) {
                $titleTh = $thaiSubpage->title;
                $bodyTh = Markdown::fromHtml($thaiSubpage->body);
                $path = strtolower($thaiSubpage->page) . '/' . $thaiSubpage->url_title;
                $to = [
                    'type' => 'Subpage',
                    'id' => $subpage->id,
                    'lng' => 'th',
                ];
            } else {
                $titleTh = null;
                $bodyTh = null;
            }

            $date = Util::createDateTimeFromPacificDb($subpage->date);

            $status = strtolower($subpage->status);
            if ($status == 'draft') {
                $draft = true;
                $deletedAt = null;
            } else if ($status == 'closed') {
                $draft = false;
                $deletedAt = $date;
            } else {
                $draft = false;
                $deletedAt = null;
            }

            Subpage::forceCreate([
                'id' => $subpage->id,
                'page' => $page,
                'subpath' => $subpage->url_title,
                'title_en' => $subpage->title,
                'title_th' => $titleTh,
                'body_en' => Markdown::fromHtml($subpage->body),
                'body_th' => $bodyTh,
                'rank' => $rank,
                'check_translation' => true,
                'draft' => $draft,
                'posted_at' => $date,
                'created_at' => $date,
                'updated_at' => Carbon::now(),
                'deleted_at' => $deletedAt,
            ]);

            if ($thaiSubpage) {
                Redirect::create(['from' => $path, 'to' => json_encode($to)]);
                Redirect::create(['from' => 'th/' . $path, 'to' => json_encode($to)]);
            }

        });

        DB::table('pages')
            ->where('title', '=', 'Subpages')
            ->update(['mahapanel' => 'no']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('pages')
            ->where('title', '=', 'Subpages')
            ->update(['mahapanel' => 'yes']);

        DB::table('subpages')->delete();
    }
}
