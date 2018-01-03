<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Talk;

class TalkTest extends TestCase
{
    public function testSetSetYoutubeIdAttribute()
    {
        $talk = new Talk;

        $talk->youtube_id = '7wFjFgklTtY';
        $this->assertEquals('7wFjFgklTtY', $talk->youtube_id);

        $talk->youtube_id = 'https://www.youtube.com/watch?v=7wFjFgklTtY&feature=youtu.be';
        $this->assertEquals('7wFjFgklTtY', $talk->youtube_id);

        $talk->youtube_id = 'https://youtu.be/7wFjFgklTtY&feature=youtu.be';
        $this->assertEquals('7wFjFgklTtY', $talk->youtube_id);

        $talk->youtube_id = 'http://www.youtube.com/embed/7wFjFgklTtY';
        $this->assertEquals('7wFjFgklTtY', $talk->youtube_id);

        $talk->youtube_id = 'http://www.youtube.com/?v=7wFjFgklTtY';
        $this->assertEquals('7wFjFgklTtY', $talk->youtube_id);

        $talk->youtube_id = 'http://www.youtube.com/e/7wFjFgklTtY';
        $this->assertEquals('7wFjFgklTtY', $talk->youtube_id);

        $talk->youtube_id = 'http://www.youtube.com/?feature=player_embedded&v=7wFjFgklTtY';
        $this->assertEquals('7wFjFgklTtY', $talk->youtube_id);
    }
}
