<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Talk;

class TalkTest extends TestCase
{

    public function testDownloadFileAttribute()
    {
        $talk = new Talk;
        $this->assertNull($talk->download_filename);

        $talk->media_path = 'foo/bar.mp3';
        $this->assertNull($talk->download_filename);

        $talk->title_en = 'A Simple Peace';
        $talk->recorded_on = '2017-12-10';
        $this->assertEquals('2017-12-10 A Simple Peace.mp3',
            $talk->download_filename);

        $talk->media_path = 'blah/blah/blah.mp4';
        $talk->title_en = 'Pavāraṇā Talks 3000';
        $talk->recorded_on = '3000-10-05';
        $this->assertEquals('3000-10-05 Pavāraṇā Talks 3000.mp4',
            $talk->download_filename);

        $talk->title_en = " Ev/\\ : \x11 File|||";
        $this->assertEquals('3000-10-05 Ev File.mp4',
            $talk->download_filename);

    }

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
