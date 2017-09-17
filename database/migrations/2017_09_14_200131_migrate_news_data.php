<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Markdown;
use App\Models\Redirect;
use App\Models\News;
use App\Util;

class MigrateNewsData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('old_news')->get()
                ->each(function($news) {

            if ($news->language !== 'English') {
                $titleEn = title_case(str_replace('-', ' ', str_slug($news->url_title)));
                $titleTh = $news->title;
                $bodyEn = null;
                $bodyTh = Markdown::fromHtml($news->body);
            } else {
                $titleEn = $news->title;
                $titleTh = null;
                $bodyEn = Markdown::fromHtml($news->body);
                $bodyTh = null;
            }

            $date = Util::createDateFromPacificDb($news->date);

            $status = strtolower($news->status);
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

            News::forceCreate([
                'id' => $news->id,
                'title_en' => $titleEn,
                'title_th' => $titleTh,
                'body_en' => $bodyEn,
                'body_th' => $bodyTh,
                'check_translation' => true,
                'image_path' => null,
                'draft' => $draft,
                'posted_at' => $date,
                'deleted_at' => $deletedAt,
            ]);

            try {
                Redirect::createFromOld('news/' . $news->url_title, [
                    'type' => 'News',
                    'id' => $news->id,
                ]);
            } catch (\Exception $e) {
                echo "Could not create redirect for {$news->id}" .
                    "/news/{$news->url_title}\n";
            }
        });

        DB::table('pages')
            ->where('title', '=', 'News')
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
            ->where('title', '=', 'News')
            ->update(['mahapanel' => 'yes']);

        DB::table('news')->delete();
        DB::table('redirects')
            ->where('from', 'LIKE', 'news/%')
            ->orWhere('from', 'LIKE', 'th/news/%')
            ->delete();
    }
}
