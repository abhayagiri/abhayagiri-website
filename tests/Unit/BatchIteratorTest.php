<?php

namespace Tests\Unit;

use App\BatchIterator;
use ArrayIterator;
use Tests\TestCase;

class BatchIteratorTest extends TestCase
{
    public function testBasicIteration()
    {
        $calls = 0;
        $iterator = new BatchIterator(function ($lastBatch) use (&$calls) {
            $calls += 1;
            if (!$lastBatch) {
                return new ArrayIterator([0, 1, 4]);
            } else {
                return false;
            }
        });
        $this->assertEquals(0, $calls);
        $this->assertTrue($iterator->valid());
        $this->assertEquals(1, $calls);
        $this->assertEquals(0, $iterator->key());
        $this->assertEquals(0, $iterator->current());
        $this->assertTrue($iterator->valid());
        $iterator->next();
        $this->assertTrue($iterator->valid());
        $this->assertEquals(1, $iterator->key());
        $this->assertEquals(1, $iterator->current());
        $this->assertTrue($iterator->valid());
        $iterator->next();
        $this->assertTrue($iterator->valid());
        $this->assertEquals(2, $iterator->key());
        $this->assertEquals(4, $iterator->current());
        $this->assertTrue($iterator->valid());
        $iterator->next();
        $this->assertEquals(1, $calls);
        $this->assertFalse($iterator->valid());
        $this->assertEquals(2, $calls);
        $this->assertNull($iterator->key());
        $this->assertNull($iterator->current());
        $this->assertFalse($iterator->valid());
        $this->assertEquals(2, $calls);
        $this->assertNull($iterator->rewind());
        $this->assertEquals(2, $calls);
        $this->assertTrue($iterator->valid());
        $this->assertEquals(3, $calls);
    }

    public function testPagedIteration()
    {
        $iterator = new BatchIterator(function ($lastBatch) {
            if (!$lastBatch) {
                $batch = new ArrayIterator(range(0, 3));
                $batch->nextPageToken = 'a';
            } elseif (!isset($lastBatch->nextPageToken)) {
                return false;
            } elseif ($lastBatch->nextPageToken === 'a') {
                $batch = new ArrayIterator(range(10, 13));
                $batch->nextPageToken = 'b';
            } elseif ($lastBatch->nextPageToken === 'b') {
                $batch = new ArrayIterator();
                $batch->nextPageToken = 'c';
            } elseif ($lastBatch->nextPageToken === 'c') {
                $batch = new ArrayIterator(range(20, 21));
            } else {
                $this->assertTrue(false, 'should not be here');
            }
            return $batch;
        });
        $this->assertEquals(
            [0, 1, 2, 3, 10, 11, 12, 13, 20, 21],
            collect($iterator)->toArray()
        );
    }

    public function testInBatches()
    {
        $i = 0;
        $iterator = new BatchIterator(function ($lastBatch) use (&$i) {
            if (!$lastBatch) {
                $i = 0;
            }
            if (++$i == 1) {
                return new ArrayIterator(range(0, 2));
            } elseif ($i == 2) {
                return new ArrayIterator(range(3, 5));
            } elseif ($i == 3) {
                return new ArrayIterator([]);
            } elseif ($i == 4) {
                return new ArrayIterator([]);
            } elseif ($i == 5) {
                return new ArrayIterator(range(6, 7));
            } else {
                return false;
            }
        });
        $batchIterator = $iterator->inBatches(2);
        $this->assertEquals(
            [[0, 1], [2, 3], [4, 5], [6, 7]],
            collect($batchIterator)->toArray()
        );
        $this->assertFalse($iterator->valid());
        $batchIterator = $iterator->inBatches(3);
        $this->assertEquals(
            [[0, 1, 2], [3, 4, 5], [6, 7]],
            collect($batchIterator)->toArray()
        );
        $this->assertFalse($iterator->valid());
        $batchIterator = $iterator->inBatches(10);
        $this->assertEquals(
            [range(0, 7)],
            collect($batchIterator)->toArray()
        );
        $this->assertFalse($iterator->valid());
        $this->assertNull($iterator->rewind());
        $this->assertTrue($iterator->valid());
    }
}
