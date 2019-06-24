<?php

namespace App\YouTubeSync;

use App\YouTubeSync\ServiceException;
use App\YouTubeSync\ServiceIterator;
use Generator;
use Google_Client;
use Google_Collection;
use Google_Model;
use Google_Service;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;

/**
 * YouTube Synchronization Service
 *
 * Iterator/generators will automatically page requests by Service::$pageSize.
 */
class Service
{
    /**
     * The number of items to request per each page of response.
     *
     * 50 is the maximum per YouTube API v3 specification.
     *
     * @var int
     */
    protected $pageSize = 50;

    /**
     * The Google API service.
     *
     * @var Google_Service
     */
    public $service;

    /**
     * The logger.
     *
     * @var Logger_Interface
     */
    public $logger;

    /**
     * Create the service manager.
     *
     * @param Google_Service          $service
     * @param Psr\Log\LoggerInterface $logger  (Optional)
     */
    public function __construct(Google_Service $service,
                                LoggerInterface $logger = null)
    {
        $this->service = $service;
        $this->logger = $logger ?? Log::getLogger();
    }

    /**
     * Return a generator of playlist details for all the playlists of a
     * YouTube channel.
     *
     * @param string $channelId
     * @return Generator
     */
    function getChannelPlaylists(string $channelId) : Generator
    {
        $iterator = $this->getIterator(
            'playlists', 'listPlaylists',
            'snippet', ['channelId' => $channelId]);
        foreach ($iterator as $playlist) {
            yield $playlist;
        }
    }

    /**
     * Return a generator of videos, with just the snippet part, for all the
     * videos of a YouTube playlist.
     *
     * @param int $playlistId
     * @return Generator
     */
    function getPlaylistVideos($playlistId) : Generator
    {
        $iterator = $this->getIterator(
            'playlistItems', 'listPlaylistItems',
            'snippet', ['playlistId' => $playlistId]);

        foreach ($iterator as $video) {
            $id = $video->snippet->resourceId->videoId ?? null;
            if ($id) {
                yield $id;
            }
        }
    }

    /**
     * Return a generator of full video details for all the videos of a YouTube
     * playlist.
     *
     * This performs two sets of requests: the first to get the snippet part
     * for each video, and the second to get contentDetails, recordingDetails
     * and status parts.
     *
     * @param string $playlistId
     * @return Generator
     */
    function getPlaylistVideosFull($playlistId) : Generator
    {
        $basicIterator = $this->getIterator(
            'playlistItems', 'listPlaylistItems',
            'snippet', ['playlistId' => $playlistId]);

        while ($basicIterator->valid()) {

            $videos = new Collection;
            for ($i = 0; $i < $this->pageSize; $i++) {
                if ($basicIterator->valid()) {
                    $video = $basicIterator->current();
                    $id = $video->snippet->resourceId->videoId ?? null;
                    if ($id) {
                        $videos[$id] = $video;
                    } else {
                        $this->logger->error('Playlist item does contain video ' .
                                             'resource id: ' .  $video->id);
                    }
                    $basicIterator->next();
                } else {
                    break;
                }
            }

            $detailIterator = $this->getIterator(
                'videos', 'listVideos',
                'contentDetails,recordingDetails,status',
                ['id' => $videos->keys()->join(',')]);

            foreach ($detailIterator as $video) {
                if ($basicVideo = $videos->pull($video->id)) {
                    $video->snippet = $basicVideo->snippet;
                    yield $video;
                } else {
                    $this->logger->warning('Unexpectedly received details for ' .
                                           'video: ' .  $video->id);
                }
            }

            // Perform an additional check to see if we got each video...
            if($videos->isNotEmpty()) {
                $this->logger->warning('Did not receive details for video(s): ' .
                                       $videos->keys()->join(', '));
            }
        }
    }

    /**
     * Return full details for one video.
     *
     * @param string $videoId
     * @return Google_Model
     * @throws App\YouTubeSync\ServiceException
     */
    public function getOneVideoDetails(string $videoId) : Google_Model
    {
        $videos = iterator_to_array($this->getVideoDetails([$videoId]));
        if (count($videos) === 1) {
            return $videos[0];
        } else {
            throw new ServiceException("Could not get video details for $videoId");
        }
    }

    /**
     * Return full details for one or more videos.
     *
     * @param iterable $videoIds
     *                 One or more video IDs.
     * @return App\YouTubeSync\ServiceIterator
     */
    public function getVideoDetails(iterable $videoIds) : ServiceIterator
    {
        $part = 'snippet,contentDetails,recordingDetails,status';
        $params = ['id' => (new Collection($videoIds))->join(',')];
        return $this->getIterator('videos', 'listVideos', $part, $params);
    }

    /**
     * Return an iterator for a service request.
     *
     * @param string $property
     * @param string $method
     * @param string $part
     * @param array  $callerParams
     *               These are merged with the iterator params.
     * @return App\YouTubeSync\ServiceIterator
     *
     * @see YouTubeSync::request()
     */
    protected function getIterator(string $property, string $method,
                                   string $part, array $callerParams)
                                   : ServiceIterator
    {
        return new ServiceIterator($this->pageSize, function($iteratorParams)
                                   use ($property, $method, $part,
                                        $callerParams) {
            $params = array_merge($callerParams, $iteratorParams);
            return $this->request($property, $method, $part, $params);
        });
    }

    /**
     * Return a response to a request for a YouTube resource.
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
        $this->logger->info('YouTube API Request', [$property, $method, $part,
                                                    $params]);
        return $this->service->{$property}->{$method}($part, $params);
    }
}
