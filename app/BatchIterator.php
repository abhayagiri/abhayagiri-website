<?php

namespace App;

use ArrayIterator;
use Closure;
use Iterator;
use Illuminate\Support\Collection;

/**
 * Batch Iterator
 *
 * This is a convience iterator that supports nifty things like automatic paged
 * responses.
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
     * The current batch.
     *
     * @var Iterator
     */
    protected $batch;

    /**
     * The closure that gets the next batch.
     *
     * @var Closure
     *
     * @see App\BatchIterator::__construct()
     */
    protected $batchHandler;

    /**
     * Create the iterator.
     *
     * The batchHandler closure should have the following function signature:
     *
     *    function (?Iterator $lastBatch) : ?Iterator
     *
     * $lastBatch is null when it's the first request. To indicate the end of
     * iteration, have the handler return null or false.
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
        if ($this->valid()) {
            return $this->batch->current();
        } else {
            return null;
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
        if ($this->valid()) {
            $this->batch->next();
            $this->index++;
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
        $this->batch = null;
    }

    /**
     * Return whether or not there are more items remaining.
     *
     * @return bool
     */
    public function valid() : bool
    {
        while (!$this->atEnd && (is_null($this->batch) ||
                                 !$this->batch->valid())) {
            $this->nextBatch();
        }
        return !$this->atEnd;
    }

    /**
     * Return a new iterator of batches of items of the current iterator of (at
     * most) size $size.
     *
     * @param int $size
     * @return App\BatchIterator
     */
    public function inBatches(int $size) : BatchIterator
    {
        return new BatchIterator(function ($batch) use ($size) {
            if (is_null($batch)) {
                $this->rewind();
            }
            if (!$this->valid()) {
                return false;
            }
            $batch = new Collection;
            while ($batch->count() < $size) {
                if (!$this->valid()) {
                    break;
                }
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
        $batch = ($this->batchHandler)($this->batch);
        if ($batch === null || $batch === false) {
            $this->batch = new ArrayIterator;
            $this->atEnd = true;
        } else {
            $this->batch = $batch;
        }
    }
}
