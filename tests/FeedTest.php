<?php

class FeedTest extends PHPUnit_Framework_TestCase
{
    public function testAudioFeed()
    {
        $feedXml = Abhayagiri\Feed::getAudioFeed();
        $this->assertNotNull($feedXml);
    }

    public function testNewsFeed()
    {
        $feedXml = Abhayagiri\Feed::getAudioFeed();
        $this->assertNotNull($feedXml);
    }
}

?>
