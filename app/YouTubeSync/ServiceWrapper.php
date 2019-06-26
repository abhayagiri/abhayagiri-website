<?php

namespace App\YouTubeSync;

use App\Models\Talk;
use App\BatchIterator;
use ArrayIterator;
use Generator;
use Google_Client;
use Google_Collection;
use Google_Service_YouTube;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
Use Iterator;

/**
 * YouTube API Service Wrapper
 *
 * A simplified interface for the YouTube API.
 */
class ServiceWrapper
{
    /**
     * 50 is the maximum page size per YouTube API v3 specification.
     */
    const DEFAULT_PAGE_SIZE = 50;

    /**
     * The default number of items to request per each page of response.
     *
     * @var int
     */
    protected $pageSize;

    /**
     * The Google YouTube API service.
     *
     * @var Google_Service_YouTube
     */
    protected $service;

    /**
     * Create the service wrapper.
     *
     * @param Google_Service_YouTube $service
     */
    public function __construct(Google_Service_YouTube $service)
    {
        $this->service = $service;
        $this->pageSize = static::DEFAULT_PAGE_SIZE;
    }

    /**
     * Return the main "uploads" playlist from a channel ID.
     *
     * @return string
     */
    public static function convertChannelPlaylistId(string $channelId) : string
    {
        // Replace the second character with a 'U' to get the "Uploads"
        // playlist..
        return $channelId[0] . 'U' . substr($channelId, 2);
    }

    /**
     * Return the Google authentication client.
     *
     * @return Google_Client
     */
    public function getClient() : Google_Client
    {
        return $this->service->getClient();
    }
    /**
     * Return the page size.
     *
     * @return int
     */
    public function getPageSize() : int
    {
        return $this->pageSize;
    }

    /**
     * Return the Google YouTube API service.
     *
     * @return Google_Service_YouTube
     */
    public function getService() : Google_Service_YouTube
    {
        return $this->service;
    }

    /**
     * Return all videos from a channel.
     *
     * This convience method performs two sets of requests: the first to get
     * the video ID for each video from the playlistItems->listPlaylistItems
     * requests, and the second to get specified $part details from the
     * videos->listVideos requests.
     *
     * @param string $part
     *               Supplied to videos->listVideos
     * @param string $channelId
     * @return App\BatchIterator
     *
     * @see https://developers.google.com/youtube/v3/docs/playlistItems/list
     * @see https://developers.google.com/youtube/v3/docs/videos/list
     */
    public function getChannelVideos(string $part, string $channelId)
                                     : BatchIterator
    {
        $playlistId = static::convertChannelPlaylistId($channelId);
        $playlistIterator = $this->getPlaylistVideos('snippet', $playlistId);
        return new BatchIterator(function ($lastBatch)
                                              use ($playlistIterator, $part) {
            if (!$lastBatch) {
                $playlistIterator->rewind();
            }
            if (!$playlistIterator->valid()) {
                return false;
            }
            $videoIds = new Collection;
            while ($videoIds->count() < $this->pageSize) {
                if (!$playlistIterator->valid()) {
                    break;
                }
                $playlistVideo = $playlistIterator->current();
                $videoId = $playlistVideo->snippet->resourceId->videoId ?? null;
                if ($videoId) {
                    $videoIds->add($videoId);
                } else {
                    Log::error('Playlist item does contain video ID: ' .
                               $playlistVideo->id);
                }
                $playlistIterator->next();
            }
            if ($videoIds->count() > 0) {
                $params = ['id' => $videoIds->join(','),
                           'maxResults' => $this->pageSize];
                return $this->request('videos', 'listVideos', $part, $params);
            } else {
                return new ArrayIterator;
            }
        });
    }

    /**
     * Return all playlists for a channel ID.
     *
     * @param string $part
     * @param string $channelId
     * @return App\BatchIterator
     *
     * @see https://developers.google.com/youtube/v3/docs/playlists/list
     */
    public function getPlaylists(string $part, string $channelId)
                                 : BatchIterator
    {
        return $this->pagedRequest('playlists', 'listPlaylists',
            $part, ['channelId' => $channelId]);
    }

    /**
     * Return all playlist videos for a playlist ID.
     *
     * @param string $part
     * @param string $playlistId
     * @return App\BatchIterator
     *
     * @see https://developers.google.com/youtube/v3/docs/playlistItems/list
     */
    public function getPlaylistVideos(string $part, string $playlistId)
                                      : BatchIterator
    {
        return $this->pagedRequest('playlistItems', 'listPlaylistItems',
            $part, ['playlistId' => $playlistId]);
    }

    /**
     * Return all videos IDs from a channel that have no associated Talk.
     *
     * This convience method that's intended to be used in conjunction with
     * getUnassociatedChannelVideos().
     *
     * @param string $channelId
     * @return App\BatchIterator
     *
     * @see App\YouTubeSync\ServiceWrapper::getUnassociatedChannelVideos()
     */
    public function getUnassociatedChannelVideoIds(string $channelId)
                                                   : BatchIterator
    {
        $playlistId = static::convertChannelPlaylistId($channelId);
        $playlistVideoBatches = $this->getPlaylistVideos('snippet', $playlistId)
                                     ->inBatches($this->getPageSize());
        return new BatchIterator(
                function ($lastBatch) use ($playlistVideoBatches) {
            if (!$lastBatch) {
                $playlistVideoBatches->rewind();
            }
            if (!$playlistVideoBatches->valid()) {
                return false;
            }
            $playlistVideoIds = $playlistVideoBatches->current()
                ->map(function ($video) {
                    return $video->snippet->resourceId->videoId ?? null;
                })->filter()->values();
            $playlistVideoBatches->next();
            $unassociatedVideoIds =
                Talk::filterAssociatedYouTubeIds($playlistVideoIds);
            return new ArrayIterator($unassociatedVideoIds->toArray());
        });
    }

    /**
     * Return all videos from a channel that have no associated Talk.
     *
     * @param string $part
     * @param string $channelId
     * @return App\BatchIterator
     *
     * @see App\YouTubeSync\ServiceWrapper::getUnassociatedChannelVideoIds()
     */
    public function getUnassociatedChannelVideos(string $part, string $channelId)
                                                 : BatchIterator
    {
        $unassociatedVideoIdBatches =
            $this->getUnassociatedChannelVideoIds($channelId)
                 ->inBatches($this->getPageSize());
        return new BatchIterator(
                function ($lastBatch) use ($unassociatedVideoIdBatches) {
            if (!$lastBatch) {
                $unassociatedVideoIdBatches->rewind();
            }
            if (!$unassociatedVideoIdBatches->valid()) {
                return false;
            }
            $unassociatedVideoIds = $unassociatedVideoIdBatches->current();
            $unassociatedVideoIdBatches->next();
            if ($unassociatedVideoIds->isNotEmpty()) {
                return $this->getVideos($part, $unassociatedVideoIds);
            } else {
                return new ArrayIterator;
            }
        });
    }

    /**
     * Return videos for one or more video IDs.
     *
     * @param string   $part
     * @param iterable $videoIds
     *                 One or more video IDs.
     * @return App\BatchIterator
     *
     * @see https://developers.google.com/youtube/v3/docs/videos/list
     */
    public function getVideos(string $part, iterable $videoIds)
                              : BatchIterator
    {
        return $this->pagedRequest('videos', 'listVideos', $part,
            ['id' => (new Collection($videoIds))->join(',')]);
    }

    /**
     * Set the page size.
     *
     * @param int $pageSize
     * @return void
     */
    public function setPageSize(int $pageSize) : void
    {
        $this->pageSize = $pageSize;
    }

    /**
     * Return an iterator for the YouTube API resource paged by
     * ServiceWrapper::$pageSize.
     *
     * @param string $property
     * @param string $method
     * @param string $part
     * @param array  $params
     * @return App\BatchIterator
     */
    protected function pagedRequest(string $property, string $method,
                                    string $part, array $params)
                                    : BatchIterator
    {
        return new BatchIterator(function ($lastBatch)
                                    use ($property, $method, $part, $params) {
            if ($lastBatch && !isset($lastBatch->nextPageToken)) {
                return false;
            }
            $params = array_merge($params, ['maxResults' => $this->pageSize]);
            if (isset($lastBatch->nextPageToken)) {
                $params['pageToken'] = $lastBatch->nextPageToken;
            }
            return $this->request($property, $method, $part, $params);
        });
    }

    /**
     * Return a response for a YouTube API resource.
     *
     * @param string $property
     *               e.g., 'videos'.
     * @param string $method
     *               e.g., 'listVideos'.
     * @param string $part
     *               e.g., 'snippet'.
     * @param array  $params
     * @return Google_Collection
     */
    protected function request(string $property, string $method, string $part,
                               array $params) : Google_Collection
    {
        dump(['YouTube API', $property, $method, $part, $params]); // TODO DEBUGGING REMOVE
        Log::info('YouTube API Request', [$property, $method, $part, $params]);
        return $this->service->{$property}->{$method}($part, $params);
    }
}
