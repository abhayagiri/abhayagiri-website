<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Redirect;
use App\Models\Subpage;

class RedirectConstructionToSubpage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $subpage = Subpage::where('page', 'about')
            ->where('subpath', 'construction')->firstOrFail();

        Redirect::createFromOld(
            'construction', [
            'type' => 'Subpage',
            'id' => $subpage->id,
        ]);

        Redirect::createFromOld(
            'construction/', [
            'type' => 'Subpage',
            'id' => $subpage->id,
        ]);

        DB::table('construction')
                ->get()->each(function($construction) use ($subpage) {
            Redirect::createFromOld(
                'construction/' . $construction->url_title, [
                'type' => 'Subpage',
                'id' => $subpage->id,
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('redirects')
            ->where('from', 'LIKE', 'construction/%')
            ->orWhere('from', 'LIKE', 'th/construction/%')
            ->delete();
    }
}
