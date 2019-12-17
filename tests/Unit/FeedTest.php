<?php

namespace Tests\Unit;

use App\Feed;
use App\Models\News;
use App\Models\Reflection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use SimplePie;
use Tests\TestCase;

class FeedTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function testAudioFeed()
    {
        Config::set('settings.talks.latest.main.playlist_group_id', 1);

        $xml = Feed::getAudioFeed();
        $feed = $this->parseFeed($xml);
        $this->assertEquals('Abhayagiri Audio', $feed->get_title());
        $this->assertNotNull($this->getPubDate($feed));
        $this->assertGreaterThan(0, $feed->get_item_quantity());
        $this->assertNotNull($feed->get_item(0)->get_date());
        $this->assertNotNull($feed->get_item(0)->get_authors());
        $this->assertNotNull($feed->get_item(0)
            ->get_item_tags('http://search.yahoo.com/mrss/', 'content'));
        $this->assertStringStartsWith('http', $feed->get_item(0)->get_link());
        $this->assertNotRegexp('_<a href="/_', $xml);
        $this->assertNotRegexp('_<img src="/_', $xml);
    }

    public function testNewsFeed()
    {
        factory(News::class)->create();
        $xml = Feed::getNewsFeed();
        $feed = $this->parseFeed($xml);
        $this->assertEquals('Abhayagiri News', $feed->get_title());
        $this->assertNotNull($this->getPubDate($feed));
        $this->assertNotNull($feed->get_item(0)->get_date());
        $this->assertNull($feed->get_item(0)->get_authors());
        $this->assertNull($feed->get_item(0)
            ->get_item_tags('http://search.yahoo.com/mrss/', 'content'));
        $this->assertStringStartsWith('http', $feed->get_item(0)->get_link());
        $this->assertNotRegexp('_<a href="/_', $xml);
        $this->assertNotRegexp('_<img src="/_', $xml);
    }

    public function testReflectionsFeed()
    {
        factory(Reflection::class)->create();
        $xml = Feed::getReflectionsFeed();
        $feed = $this->parseFeed($xml);
        $this->assertEquals('Abhayagiri Reflections', $feed->get_title());
        $this->assertNotNull($this->getPubDate($feed));
        $this->assertNotNull($feed->get_item(0)->get_date());
        $this->assertNotNull($feed->get_item(0)->get_authors());
        $this->assertNotNull($feed->get_item(0)
            ->get_item_tags('http://search.yahoo.com/mrss/', 'content'));
        $this->assertStringStartsWith('http', $feed->get_item(0)->get_link());
        $this->assertNotRegexp('_<a href="/_', $xml);
        $this->assertNotRegexp('_<img src="/_', $xml);
    }

    protected function parseFeed($xml)
    {
        $feed = new SimplePie();
        $feed->set_raw_data($xml);
        $feed->init();
        $feed->handle_content_type();
        return $feed;
    }

    protected function getPubDate($feed)
    {
        return $feed->get_channel_tags('', 'pubDate')[0]['data'];
    }
}
