<?php

namespace App\YouTubeSync;

use Closure;
use Google_Collection;
use Illuminate\Support\Collection;
use Iterator;

/**
 * This is a convience iterator that handles the paged request/response for the
 * Google YouTube v3 API.
 */
class ServiceIterator implements Iterator
{
    /**
     * The number of items to request per each page of response.
     *
     * @var int
     */
    protected $pageSize = 50;

    /**
     * The callback that handles the service request.
     *
     * @var \Closure
     */
    protected $serviceHandler;

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
     * The latest response from server handler.
     *
     * @var \Google_Collection
     */
    protected $response;

    /**
     * Create the iterator.
     *
     * @param  int  $pageSize
     * @param  \Closure  $serviceHandler
     */
    public function __construct(int $pageSize, Closure $serviceHandler)
    {
        $this->pageSize = $pageSize;
        $this->serviceHandler = $serviceHandler;
        $this->rewind();
    }

    /**
     * Return the current item or null.
     *
     * @return \Google_Model|null
     */
    public function current()
    {
        if ($this->atEnd) {
            return null;
        } else if ($this->response) {
            return $this->response->current();
        } else {
            $this->iterateResponse();
            return $this->current();
        }
    }

    /**
     * Return the current index or null if at the end.
     *
     * @return int|null
     */
    public function key()
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
            if ($this->response) {
                $this->response->next();
                $this->index++;
                if (!$this->response->valid()) {
                    if (isset($this->response->nextPageToken)) {
                        $this->iterateResponse();
                    } else {
                        $this->atEnd = true;
                    }
                }
            } else {
                $this->iterateResponse();
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
        $this->response = null;
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
        } else if ($this->response) {
            return true;
        } else {
            $this->iterateResponse();
            return !$this->atEnd;
        }
    }

    /**
     * Returns the last response.
     *
     * @return \Google_Collection
     */
    public function lastResponse() : Google_Collection
    {
        $this->valid(); // Ensure a response
        return $this->response;
    }

    /**
     * Update response to the next page of items.
     *
     * @return void
     */
    protected function iterateResponse() : void
    {
        $params = ['maxResults' => $this->pageSize];
        if (isset($this->response->nextPageToken)) {
            $params['pageToken'] = $this->response->nextPageToken;
        }
        $this->response = ($this->serviceHandler)($params);
        if (!$this->response->valid()) {
            $this->atEnd = true;
        }
    }
}
