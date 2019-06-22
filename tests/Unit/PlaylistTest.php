<?php

namespace Tests\Unit;

use App\Models\Playlist;
use Tests\TestCase;

class PlaylistTest extends TestCase
{
    public function testSetYoutubeIdAttribute()
    {
        $playlist = new Playlist;

        $playlist->youtube_id = 'PLoNO26iBjavYfEIwwo7KHP99fQfdUiFcR';
        $this->assertEquals('PLoNO26iBjavYfEIwwo7KHP99fQfdUiFcR', $playlist->youtube_id);

        $playlist->youtube_id = 'https://www.youtube.com/playlist?list=PLoNO26iBjavYfEIwwo7KHP99fQfdUiFcR';
        $this->assertEquals('PLoNO26iBjavYfEIwwo7KHP99fQfdUiFcR', $playlist->youtube_id);

        $playlist->youtube_id = 'https://www.youtube.com/watch?v=JPtf7hB3Afc&list=PLoNO26iBjavYfEIwwo7KHP99fQfdUiFcR&index=4&t=246s';
        $this->assertEquals('PLoNO26iBjavYfEIwwo7KHP99fQfdUiFcR', $playlist->youtube_id);

        $playlist->youtube_id = 'http://www.youtube.com/embed/7wFjFgklTtY';
        $this->assertEquals('http://www.youtube.com/embed/7wFjFgklTtY', $playlist->youtube_id);
    }
}
