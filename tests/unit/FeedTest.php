<?php

class FeedTest extends PHPUnit_Framework_TestCase
{
    public function testAudioFeed()
    {
        $xml = Abhayagiri\Feed::getAudioFeed();
        $feed = $this->parseFeed($xml);
        $this->assertEquals('Abhayagiri Audio', $feed->get_title());
        $this->assertNotNull($this->getPubDate($feed));
    }

    public function testNewsFeed()
    {
        $xml = Abhayagiri\Feed::getNewsFeed();
        $feed = $this->parseFeed($xml);
        $this->assertEquals('Abhayagiri News', $feed->get_title());
        $this->assertNotNull($this->getPubDate($feed));
    }

    public function testReflectionsFeed()
    {
        $xml = Abhayagiri\Feed::getReflectionsFeed();
        $feed = $this->parseFeed($xml);
        $this->assertEquals('Abhayagiri Reflections', $feed->get_title());
        $this->assertNotNull($this->getPubDate($feed));
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

?>
