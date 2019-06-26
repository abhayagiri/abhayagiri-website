<?php

namespace Tests\Unit\YouTubeSync;

use App\YouTubeSync\BatchIterator;
use ArrayIterator;
use Tests\TestCase;

class BatchIteratorTest extends TestCase
{
    public function testBasicIteration()
    {
        $iterator = new BatchIterator(function($lastBatch) {
            if ($lastBatch) {
                return false;
            } else {
                return new ArrayIterator([0, 1, 4]);
            }
        });
        $this->assertTrue($iterator->valid());
        $this->assertEquals(0, $iterator->key());
        $this->assertEquals(0, $iterator->current());
        $iterator->next();
        $this->assertTrue($iterator->valid());
        $this->assertEquals(1, $iterator->key());
        $this->assertEquals(1, $iterator->current());
        $iterator->next();
        $this->assertTrue($iterator->valid());
        $this->assertEquals(2, $iterator->key());
        $this->assertEquals(4, $iterator->current());
        $iterator->next();
        $this->assertFalse($iterator->valid());
        $this->assertNull($iterator->key());
        $this->assertNull($iterator->current());
    }

    public function testPagedIteration()
    {
        $iterator = new BatchIterator(function ($lastBatch) {
            if (!$lastBatch) {
                $batch = new ArrayIterator(range(0, 3));
                $batch->nextPageToken = 'a';
            } else if (!isset($lastBatch->nextPageToken)) {
                return false;
            } else if ($lastBatch->nextPageToken === 'a') {
                $batch = new ArrayIterator(range(10, 13));
                $batch->nextPageToken = 'b';
            } else if ($lastBatch->nextPageToken === 'b') {
                $batch = new ArrayIterator(range(20, 21));
            } else {
                $this->assertTrue(false, 'should not be here');
            }
            return $batch;
        });
        $this->assertEquals([0, 1, 2, 3, 10, 11, 12, 13, 20, 21],
                            collect($iterator)->toArray());
    }

    public function testInBatches()
    {
        $i = 0;
        $iterator = new BatchIterator(function($lastBatch) use (&$i) {
            if (!$lastBatch) {
                $i = 0;
            }
            if (++$i == 1) {
                return new ArrayIterator(range(0, 2));
            } else if ($i == 2) {
                return new ArrayIterator(range(3, 5));
            } else if ($i == 3) {
                return new ArrayIterator(range(6, 7));
            } else {
                return false;
            }
        });
        $batchIterator = $iterator->inBatches(2);
        $this->assertEquals([[0, 1], [2, 3], [4, 5], [6, 7]],
                            collect($batchIterator)->toArray());
        $this->assertNull($iterator->current());
        $this->assertEquals([[0, 1], [2, 3], [4, 5], [6, 7]],
                            collect($batchIterator)->toArray());
    }
}
