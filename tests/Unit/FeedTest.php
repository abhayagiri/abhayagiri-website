<?php

namespace Tests\Unit;

use App\Feed;
use Tests\TestCase;

class FeedTest extends TestCase
{
    public function testGenerateFeed()
    {
        $feed = new Feed('talks', 'atom', 'en');
        $xml = $feed->generateFeed();
        $this->assertStringContainsString('Abhayagiri Talks', $xml);
        $this->assertStringContainsString('http://www.w3.org/2005/Atom', $xml);
    }
}
