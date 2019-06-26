<?php

namespace App\YouTubeSync;

use ArrayIterator;
use Closure;
use Iterator;
use Illuminate\Support\Collection;

/**
 * Batch Iterator
 *
 * This is a convience iterator that provides support nifty things like
 * automatic paged responses.
 *
 * See App\YouTubeSync\ServiceWrapper
 */
class BatchIterator implements Iterator
{
    /**
     * Are we at the end?
     *
     * @var bool
     */
    protected $atEnd;

    /**
     * The current index (key).
     *
     * @var bool
     */
    protected $index;

    /**
     * The last batch.
     *
     * @var Iterator
     */
    protected $lastBatch;

    /**
     * The closure that gets the next batch.
     *
     * @var Closure
     *
     * @see App\YouTubeSync\BatchIterator::__construct()
     */
    protected $batchHandler;

    /**
     * Create the iterator.
     *
     * The batchHandler closure should have the following function signature:
     *
     *    function (?Iterator $lastBatch) : ?Iterator
     *
     * If $lastBatch is null, this means it's the first batch (rewind). If
     * the function returns null or false, this will signal the end of
     * iteration.
     *
     * @param Closure $batchHandler
     */
    public function __construct(Closure $batchHandler)
    {
        $this->batchHandler = $batchHandler;
        $this->rewind();
    }

    /**
     * Return the current item or null.
     *
     * @return mixed|null
     */
    public function current()
    {
        if ($this->atEnd) {
            return null;
        } else if ($this->lastBatch) {
            return $this->lastBatch->current();
        } else {
            $this->nextBatch();
            return $this->current();
        }
    }

    /**
     * Return the current index or null if at the end.
     *
     * @return int|null
     */
    public function key() : ?int
    {
        if ($this->valid()) {
            return $this->index;
        } else {
            return null;
        }
    }

    /**
     * Iterate to the next item.
     *
     * @return void
     */
    public function next() : void
    {
        if (!$this->atEnd) {
            if ($this->lastBatch) {
                $this->lastBatch->next();
                $this->index++;
                if (!$this->lastBatch->valid()) {
                    $this->nextBatch();
                }
            } else {
                $this->nextBatch();
                $this->next();
            }
        }
    }

    /**
     * Restart the iterator.
     *
     * @return void
     */
    public function rewind() : void
    {
        $this->atEnd = false;
        $this->index = 0;
        $this->lastBatch = null;
    }

    /**
     * Return whether or not there are more items remaining.
     *
     * @return bool
     */
    public function valid() : bool
    {
        if ($this->atEnd) {
            return false;
        } else if ($this->lastBatch) {
            return true;
        } else {
            $this->nextBatch();
            return !$this->atEnd;
        }
    }

    /**
     * Return the last batch.
     *
     * @return Iterator
     */
    public function getLastBatch() : Iterator
    {
        $this->valid(); // Ensure a batch
        return $this->lastBatch;
    }

    /**
     * Return a new iterator of batches of items of the current iterator of (at
     * most) size $size.
     *
     * @param int $size
     * @return App\YouTubeSync\BatchIterator
     */
    public function inBatches(int $size) : BatchIterator
    {
        return new BatchIterator(function ($lastBatch) use ($size) {
            if (is_null($lastBatch)) {
                $this->rewind();
            }
            $batch = new Collection;
            while ($this->valid() && $batch->count() < $size) {
                $batch->add($this->current());
                $this->next();
            }
            if ($batch->isNotEmpty()) {
                return new ArrayIterator([$batch]);
            } else {
                return false;
            }
        });
    }

    /**
     * Go to the next batch.
     *
     * @return void
     */
    protected function nextBatch() : void
    {
        $batch = ($this->batchHandler)($this->lastBatch);
        if ($batch === null || $batch === false) {
            $this->lastBatch = new ArrayIterator;
            $this->atEnd = true;
        } else {
            $this->lastBatch = $batch;
        }
    }
}
