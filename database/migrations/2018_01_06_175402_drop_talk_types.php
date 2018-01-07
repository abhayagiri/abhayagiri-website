<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Playlist;
use App\Models\Redirect;
use App\Models\Talk;

class DropTalkTypes extends Migration
{

    protected $idToUrlRedirect = [
        6492 => 'http://dharmaseed.org/retreats/2919', // Body, Heart, and Mind: Embodying Citta
    ];

    protected $idToPlaylistRedirect = [
        4969 => 1, // Metta Retreat 2008 Entire
        5371 => 2, // Monastic Retreat 2008
        5394 => 3, // The Pilgrim Kamanita (Audio Book)
        5449 => 4, // Thanksgiving Retreat 2006
        5678 => 5, // Feeling Abundance Spirit Rock Daylong Entire
        5711 => 6, // Thanksgiving Monastic Retreat 2012 Entire
        6080 => 7, // Abhayagiri 2013 Winter Retreat
        6086 => 8, // Anapanasati - 19 Talks from Winter Retreat 2005
        6087 => 9, // Viriya - 26 Talks from Winter Retreat 2005
        6207 => 10, // Abhayagiri 2014 Winter Retreat DVD
        6279 => 11, // 2014 Thanksgiving Monastic Retreat Entire
        6324 => 12, // Abhayagiri 2015 Winter Retreat
        6420 => 13, // 2015 Thanksgiving Monastic Retreat Entire
        6483 => 14, // 20th Anniversary Compilation
    ];

    protected $typeToPlaylists = [
        4 => 103, // Question and Answer Sessions
        5 => 102, // Chanting
        6 => 77, // Morning Reflections
        7 => 76, // Guided Meditation
        9 => 78, // Upāsikā Day Sessions
        10 => 75, // Readings
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Talk::withoutGlobalScopes()->each(function ($talk) {
            if ($talk->playlists->count() == 0) {
                $playlistId = array_get($this->idToPlaylistRedirect, $talk->id);
                if ($playlistId) {
                    $playlist = Playlist::find($playlistId);
                    if (!$playlist) {
                        dump(['Playlist not found' . $talk->id]);
                        return;
                    }
                    $this->createRedirect($talk, [
                        'type' => 'Playlist',
                        'id' => $playlist->id,
                    ]);
                    // dump(['Delete', $talk->id]);
                    $talk->forceDelete();
                    return;
                }
                $url = array_get($this->idToUrlRedirect, $talk->id);
                if ($url) {
                    $this->createRedirect($talk, [
                        'type' => 'Basic',
                        'to' => $url,
                    ]);
                    // dump(['Delete', $talk->id]);
                    $talk->forceDelete();
                    return;
                }
                if ($talk->type_id === 2) { // Dhamma Talks
                    $title = 'Dhamma Talks ' . $talk->recorded_on->year;
                    $playlist = Playlist::where('title_en', '=', $title)->first();
                    if (!$playlist) {
                        dump(['Dhamma talk playlist not found', $talk->id]);
                        return;
                    }
                } else {
                    $playlistId = array_get($this->typeToPlaylists, $talk->type_id);
                    $playlist = $playlistId ? Playlist::find($playlistId) : null;
                    if (!$playlist) {
                        dump(['Playlist map not found', $talk->id]);
                        return;
                    }
                }
                // dump(['Add playlist to talk', $talk->id, $playlist->id]);
                $talk->playlists()->save($playlist);
            }
        });
        Schema::table('talks', function(Blueprint $table) {
            $table->dropForeign(['type_id']);
            $table->dropColumn('type_id');
        });
        Schema::drop('talk_types');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No going back
    }

    protected function createRedirect($talk, $options)
    {
        $from = substr($talk->path, 1);
        // dump(['From talk create redirect', $talk->id, $from, $options]);
        Redirect::createFromOld($from, $options);
        $this->modifyOldRedirect($talk, $options, 'en');
        $this->modifyOldRedirect($talk, $options, 'th');
    }

    protected function modifyOldRedirect($talk, $options, $lng)
    {
        $toMatch = '{"type":"talks","lng":"' . $lng . '","id":' . $talk->id . '}';
        $redirect = Redirect::where('to', '=', $toMatch)->first();
        if (!$redirect) {
            $toMatch = '{"type":"talks","id":' . $talk->id . ',"lng":"' . $lng . '"}';
            $redirect = Redirect::where('to', '=', $toMatch)->first();
            if (!$redirect) {
                dump(['Error', 'redirect not found', $talk->id]);
            }
        }
        // dump(['Modify old redirect', $redirect->id]);
        $redirect->to = json_encode(array_merge($options, [ 'lng' => $lng ]));
        $redirect->save();
    }
}
