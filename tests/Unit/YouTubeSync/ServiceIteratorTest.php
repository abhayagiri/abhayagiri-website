<?php

namespace Tests\Unit\YouTubeSync;

use App\YouTubeSync\ServiceIterator;
use ArrayIterator;
use Tests\TestCase;

class ServiceIteratorTest extends TestCase
{
    public function testBasicIteration()
    {
        $i = new ServiceIterator(5, function($params) {
            return new ArrayIterator([0, 1, 4]);
        });
        $this->assertTrue($i->valid());
        $this->assertEquals(0, $i->key());
        $this->assertEquals(0, $i->current());
        $i->next();
        $this->assertTrue($i->valid());
        $this->assertEquals(1, $i->key());
        $this->assertEquals(1, $i->current());
        $i->next();
        $this->assertTrue($i->valid());
        $this->assertEquals(2, $i->key());
        $this->assertEquals(4, $i->current());
        $i->next();
        $this->assertFalse($i->valid());
        $this->assertNull($i->key());
        $this->assertNull($i->current());
    }

    public function testPagedIteration()
    {
        $requests = 0;
        $i = new ServiceIterator(4, function($params) use (&$requests) {
            if ($requests >= 4) {
                $this->assertFalse(true, 'Too many requests');
            } else if (!isset($params['pageToken'])) {
                $this->assertEquals(0, $requests);
                $response = new ArrayIterator(range(0, 3));
                $response->nextPageToken = 'a';
            } else if ($params['pageToken'] == 'a') {
                $this->assertEquals(1, $requests);
                $response = new ArrayIterator(range(10, 13));
                $response->nextPageToken = 'b';
            } else {
                $this->assertEquals(2, $requests);
                $response = new ArrayIterator(range(20, 21));
            }
            $this->assertEquals(4, $params['maxResults']);
            $requests++;
            return $response;
        });
        $this->assertEquals([0, 1, 2, 3, 10, 11, 12, 13, 20, 21],
                            iterator_to_array($i));
        $this->assertEquals(3, $requests);
    }
}
