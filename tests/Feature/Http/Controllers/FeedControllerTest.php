<?php

namespace Tests\Unit;

use App\Feed;
use App\Models\News;
use App\Models\Playlist;
use App\Models\PlaylistGroup;
use App\Models\Reflection;
use App\Models\Tale;
use App\Models\Talk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use SimplePie;
use Tests\TestCase;

class FeedControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        factory(Tale::class, 2)->create();
        factory(Tale::class, 2)->create(['title_th' => null, 'body_th' => null]);
        factory(News::class, 3)->create();
        factory(News::class, 2)->create(['title_th' => null, 'body_th' => null]);
        factory(Reflection::class, 3)->create();
        $playlistGroup = factory(PlaylistGroup::class)->create();
        $playlist = factory(Playlist::class)->create([
            'group_id' => $playlistGroup->id],
        );
        Config::set('settings.talks.latest.main.playlist_group_id', $playlistGroup->id);
        factory(Talk::class, 2)->create([
            'media_path' => '123.mp3',
        ])->each(function ($talk) use ($playlist) {
            $talk->playlists()->sync($playlist->id);
        });
    }

    public function testMaxItems()
    {
        factory(Tale::class, 30)->create();
        $response = $this->get('/audio.rss');
        $feed = $this->getFeed(route('tales.atom'));
        $this->assertFeedIsOkay($feed);
        $this->assertEquals(20, $feed->get_item_quantity());
    }

    public function testOldRss()
    {
        $response = $this->get('/audio.rss');
        $response
            ->assertOk()
            ->assertSee('Abhayagiri Talks');

        $response = $this->get('/rss/audio.php');
        $response
            ->assertOk()
            ->assertSee('Abhayagiri Talks');

        $response = $this->get('/rss/news.php');
        $response
            ->assertOk()
            ->assertSee('Abhayagiri News');

        $response = $this->get('/rss/reflections.php');
        $response
            ->assertOk()
            ->assertSee('Abhayagiri Reflections');
    }

    public function testNewsAtomFeed()
    {
        $feed = $this->getFeed(route('news.atom'));
        $this->assertEquals('Abhayagiri News', $feed->get_title());
        $this->assertFeedIsAtom($feed);
        $this->assertFeedIsOkay($feed);
        $this->assertEquals(5, $feed->get_item_quantity());
    }

    public function testNewsRssFeed()
    {
        $feed = $this->getFeed(route('news.rss'));
        $this->assertEquals('Abhayagiri News', $feed->get_title());
        $this->assertFeedIsRss($feed);
        $this->assertFeedIsOkay($feed);
        $this->assertEquals(5, $feed->get_item_quantity());
    }

    public function testReflectionsAtomFeed()
    {
        $feed = $this->getFeed(route('reflections.atom'));
        $this->assertEquals('Abhayagiri Reflections', $feed->get_title());
        $this->assertFeedIsAtom($feed);
        $this->assertFeedIsOkay($feed);
    }

    public function testReflectionsRssFeed()
    {
        $feed = $this->getFeed(route('reflections.rss'));
        $this->assertEquals('Abhayagiri Reflections', $feed->get_title());
        $this->assertFeedIsRss($feed);
        $this->assertFeedIsOkay($feed);
    }

    public function testTalesAtomFeed()
    {
        $feed = $this->getFeed(route('tales.atom'));
        $this->assertEquals('Abhayagiri Tales', $feed->get_title());
        $this->assertFeedIsAtom($feed);
        $this->assertFeedIsOkay($feed);
        $this->assertFeedItemHasImage($feed->get_item(0));
        $this->assertEquals(4, $feed->get_item_quantity());
    }

    public function testTalesRssFeed()
    {
        $feed = $this->getFeed(route('tales.rss'));
        $this->assertEquals('Abhayagiri Tales', $feed->get_title());
        $this->assertFeedIsRss($feed);
        $this->assertFeedIsOkay($feed);
        $this->assertFeedItemHasImage($feed->get_item(0));
        $this->assertEquals(4, $feed->get_item_quantity());
    }

    public function testTalksAtomFeed()
    {
        $feed = $this->getFeed(route('talks.atom'));
        $this->assertEquals('Abhayagiri Talks', $feed->get_title());
        $this->assertFeedIsAtom($feed);
        $this->assertFeedIsOkay($feed);
        $this->assertFeedItemHasImage($feed->get_item(0));
        $this->assertFeedItemHasEnclosure($feed->get_item(0));
        $this->assertEquals(2, $feed->get_item_quantity());
    }

    public function testTalksRssFeed()
    {
        $feed = $this->getFeed(route('talks.rss'));
        $this->assertFeedIsRss($feed);
        $this->assertFeedIsOkay($feed);
        $this->assertFeedItemHasImage($feed->get_item(0));
        $this->assertFeedItemHasEnclosure($feed->get_item(0));
        $this->assertEquals(2, $feed->get_item_quantity());
    }

    public function testThaiNewsAtomFeed()
    {
        $feed = $this->getFeed(route('th.news.atom'));
        $this->assertEquals('อภัยคีรี ข่าว', $feed->get_title());
        $this->assertFeedIsAtom($feed);
        $this->assertFeedIsOkay($feed);
        $this->assertEquals(3, $feed->get_item_quantity());
    }

    public function testThaiNewsRssFeed()
    {
        $feed = $this->getFeed(route('th.news.rss'));
        $this->assertEquals('อภัยคีรี ข่าว', $feed->get_title());
        $this->assertFeedIsRss($feed);
        $this->assertFeedIsOkay($feed);
        $this->assertEquals(3, $feed->get_item_quantity());
    }

    public function testThaiTalesAtomFeed()
    {
        $feed = $this->getFeed(route('th.tales.atom'));
        $this->assertEquals('อภัยคีรี เรื่องเล่า', $feed->get_title());
        $this->assertFeedIsAtom($feed);
        $this->assertFeedIsOkay($feed);
        $this->assertFeedItemHasImage($feed->get_item(0));
        $this->assertEquals(2, $feed->get_item_quantity());
    }

    public function testThaiTalesRssFeed()
    {
        $feed = $this->getFeed(route('th.tales.rss'));
        $this->assertEquals('อภัยคีรี เรื่องเล่า', $feed->get_title());
        $this->assertFeedIsRss($feed);
        $this->assertFeedIsOkay($feed);
        $this->assertFeedItemHasImage($feed->get_item(0));
        $this->assertEquals(2, $feed->get_item_quantity());
    }

    public function testThaiTalksAtomFeed()
    {
        $feed = $this->getFeed(route('th.talks.atom'));
        $this->assertEquals('อภัยคีรี เสียงธรรม', $feed->get_title());
        $this->assertFeedIsAtom($feed);
        $this->assertFeedIsOkay($feed);
        $this->assertFeedItemHasImage($feed->get_item(0));
        $this->assertFeedItemHasEnclosure($feed->get_item(0));
        $this->assertEquals(2, $feed->get_item_quantity());
    }

    public function testThaiTalksRssFeed()
    {
        $feed = $this->getFeed(route('th.talks.rss'));
        $this->assertEquals('อภัยคีรี เสียงธรรม', $feed->get_title());
        $this->assertFeedIsRss($feed);
        $this->assertFeedIsOkay($feed);
        $this->assertFeedItemHasImage($feed->get_item(0));
        $this->assertFeedItemHasEnclosure($feed->get_item(0));
        $this->assertEquals(2, $feed->get_item_quantity());
    }

    protected function assertFeedItemHasEnclosure($item)
    {
        $enclosure = $item->get_enclosure();
        $this->assertNotNull($enclosure);
        $this->assertStringStartsWith('http', $enclosure->link);
    }

    protected function assertFeedItemHasImage($item)
    {
        $this->assertNotNull($item->get_item_tags('http://search.yahoo.com/mrss/', 'content'));
    }

    protected function assertFeedIsAtom($feed)
    {
        $this->assertTrue($feed->get_type() == SIMPLEPIE_TYPE_ATOM_10);
        $pubDate = $feed->get_feed_tags(SIMPLEPIE_NAMESPACE_ATOM_10, 'pubDate')[0]['data'];
        $this->assertNotNull($pubDate);
    }

    protected function assertFeedIsRss($feed)
    {
        $this->assertTrue($feed->get_type() == SIMPLEPIE_TYPE_RSS_20);
        $pubDate = $feed->get_channel_tags('', 'pubDate')[0]['data'];
        $this->assertNotNull($pubDate);
    }

    protected function assertFeedIsOkay($feed)
    {
        $this->assertNotNull($feed->get_title());
        $this->assertNotNull($feed->get_description());
        $this->assertGreaterThan(0, $feed->get_item_quantity());
        $item = $feed->get_item(0);
        $this->assertNotNull($item->get_title());
        $this->assertNotNull($item->get_date());
        $this->assertStringStartsWith('http', $item->get_link());
        $this->assertNotNull($item->get_authors());
        $this->assertNotRegexp('_<a href="/_', $feed->raw_data);
        $this->assertNotRegexp('_<img src="/_', $feed->raw_data);
    }

    protected function getFeed($url)
    {
        $response = $this->get($url);
        $feed = new SimplePie();
        $feed->set_raw_data($response->getContent());
        $feed->init();
        $feed->handle_content_type($response->headers->get('Content-Type'));
        return $feed;
    }
}
