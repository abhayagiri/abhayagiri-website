<?php

namespace Tests\Unit;

use App\Models\Author;
use App\Models\Talk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TalkTest extends TestCase
{
    use RefreshDatabase;

    public function testDownloadFileAttribute()
    {
        $talk = new Talk;
        $this->assertNull($talk->download_filename);

        $talk->media_path = 'foo/bar.mp3';
        $this->assertNull($talk->download_filename);

        $talk->title_en = 'A Simple Peace';
        $talk->recorded_on = '2017-12-10';
        $this->assertEquals(
            '2017-12-10 A Simple Peace.mp3',
            $talk->download_filename
        );

        $talk->media_path = 'blah/blah/blah.mp4';
        $talk->title_en = 'Pavāraṇā Talks 3000';
        $talk->recorded_on = '3000-10-05';
        $this->assertEquals(
            '3000-10-05 Pavāraṇā Talks 3000.mp4',
            $talk->download_filename
        );

        $talk->title_en = " Ev/\\ : \x11 File|||";
        $this->assertEquals(
            '3000-10-05 Ev File.mp4',
            $talk->download_filename
        );
    }

    public function testFilterYouTubeVideoIds()
    {
        $this->seed();
        $talks = factory(Talk::class, 3)->create();
        $talks[1]->delete(); // soft-delete
        $idsToAdd = Talk::filterYouTubeVideoIds(['abc123',
            $talks[0]->youtube_video_id, $talks[1]->youtube_video_id]);
        $this->assertEquals(['abc123'], $idsToAdd->toArray());
    }

    public function testGetYoutubeNormalizedTitleAttribute()
    {
        $author = new Author(['title_en' => 'Ajahn']);
        $talk = new Talk(['title_en' => 'Dhamma']);
        $talk->author = $author;
        $this->assertEquals('Dhamma | Ajahn', $talk->youtubeNormalizedTitle);
    }

    public function testSetYoutubeIdAttribute()
    {
        $talk = new Talk;

        $talk->youtube_video_id = '7wFjFgklTtY';
        $this->assertEquals('7wFjFgklTtY', $talk->youtube_video_id);

        $talk->youtube_video_id = 'https://www.youtube.com/watch?v=7wFjFgklTtY&feature=youtu.be';
        $this->assertEquals('7wFjFgklTtY', $talk->youtube_video_id);

        $talk->youtube_video_id = 'https://youtu.be/7wFjFgklTtY&feature=youtu.be';
        $this->assertEquals('7wFjFgklTtY', $talk->youtube_video_id);

        $talk->youtube_video_id = 'http://www.youtube.com/embed/7wFjFgklTtY';
        $this->assertEquals('7wFjFgklTtY', $talk->youtube_video_id);

        $talk->youtube_video_id = 'http://www.youtube.com/?v=7wFjFgklTtY';
        $this->assertEquals('7wFjFgklTtY', $talk->youtube_video_id);

        $talk->youtube_video_id = 'http://www.youtube.com/e/7wFjFgklTtY';
        $this->assertEquals('7wFjFgklTtY', $talk->youtube_video_id);

        $talk->youtube_video_id = 'http://www.youtube.com/?feature=player_embedded&v=7wFjFgklTtY';
        $this->assertEquals('7wFjFgklTtY', $talk->youtube_video_id);
    }
}
