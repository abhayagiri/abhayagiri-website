<?php

namespace Tests\Unit;

use App\Models\Playlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlaylistTest extends TestCase
{
    use RefreshDatabase;

    public function testFilterYouTubePlaylistIds()
    {
        $playlists = factory(Playlist::class, 3)->create();
        $playlists[1]->delete(); // soft-delete
        $idsToAdd = Playlist::filterYouTubePlaylistIds(['abc123',
            $playlists[0]->youtube_playlist_id,
            $playlists[1]->youtube_playlist_id]);
        $this->assertEquals(['abc123'], $idsToAdd->toArray());
    }

    public function testSetYoutubePlaylistIdAttribute()
    {
        $playlist = new Playlist;

        $playlist->youtube_playlist_id =
            'PLoNO26iBjavYfEIwwo7KHP99fQfdUiFcR';
        $this->assertEquals(
            'PLoNO26iBjavYfEIwwo7KHP99fQfdUiFcR',
            $playlist->youtube_playlist_id
        );

        $playlist->youtube_playlist_id =
            'https://www.youtube.com/playlist?list=PLoNO26iBjavYfEIwwo7KHP99fQfdUiFcR';
        $this->assertEquals(
            'PLoNO26iBjavYfEIwwo7KHP99fQfdUiFcR',
            $playlist->youtube_playlist_id
        );

        $playlist->youtube_playlist_id =
            'https://www.youtube.com/watch?v=JPtf7hB3Afc&list=PLoNO26iBjavYfEIwwo7KHP99fQfdUiFcR&index=4&t=246s';
        $this->assertEquals(
            'PLoNO26iBjavYfEIwwo7KHP99fQfdUiFcR',
            $playlist->youtube_playlist_id
        );

        $playlist->youtube_playlist_id =
            'http://www.youtube.com/embed/7wFjFgklTtY';
        $this->assertEquals(
            'http://www.youtube.com/embed/7wFjFgklTtY',
            $playlist->youtube_playlist_id
        );
    }
}
