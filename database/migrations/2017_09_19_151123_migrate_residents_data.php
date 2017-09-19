<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Markdown;
use App\Models\Redirect;
use App\Models\Resident;
use App\Util;

class MigrateResidentsData extends Migration
{
    protected $thaiSearchMap = [
        'nyaniko' => 'naniko',
        'thitapanyo' => 'thitapanno',
        'suddhiko' => 'syddhiko',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $rank = 0;
        DB::table('old_residents')
                ->where('language', 'English')
                ->orderBy('date', 'desc')
                ->get()->each(function($resident) use (&$rank) {

            $rank += 1;
            $basePath = 'community/residents/';
            $to = [
                'type' => 'Resident',
                'id' => $resident->id,
                'lng' => 'th',
            ];
            $slug = strtolower($resident->url_title);
            $slug = preg_replace('/^(ajahn|tan)-/', '', $slug);

            if ($slug !== $resident->url_title) {
                Redirect::createFromOld($basePath . $resident->url_title, $to);
            }

            $thaiSearchSlug = '%' . array_get($this->thaiSearchMap, $slug, $slug) . '%thai';
            $thaiResident = DB::table('old_residents')
                ->where('url_title', 'LIKE', $thaiSearchSlug)
                ->first();

            if ($thaiResident) {
                $checkTranslation = false;
                $titleTh = $thaiResident->title;
                $descriptionTh = Markdown::fromHtml($thaiResident->body);
                $thaiPath = $basePath . $thaiResident->url_title;
                Redirect::create(['from' => $thaiPath, 'to' => json_encode($to)]);
                Redirect::create(['from' => 'th/' . $thaiPath, 'to' => json_encode($to)]);
            } else {
                dump('Could not find thai resident for ' . $resident->id);
                $checkTranslation = true;
                $titleTh = null;
                $descriptionTh = null;
            }

            $date = Util::createDateTimeFromPacificDb($resident->date);
            $status = strtolower($resident->resident_status);

            Resident::forceCreate([
                'id' => $resident->id,
                'slug' => $slug,
                'title_en' => $resident->title,
                'title_th' => $titleTh,
                'description_en' => Markdown::fromHtml($resident->body),
                'description_th' => $descriptionTh,
                'check_translation' => $checkTranslation,
                'image_path' => 'images/residents/' . $resident->photo,
                'rank' => $rank,
                'status' => $status,
                'created_at' => $date,
                'updated_at' => Carbon::now(),
                'deleted_at' => null,
            ]);

        });

        DB::table('pages')
            ->where('title', '=', 'Residents')
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
            ->where('title', '=', 'Residents')
            ->update(['mahapanel' => 'yes']);
    }
}
