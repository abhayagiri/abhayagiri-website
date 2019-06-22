<?php

namespace App\YouTubeSync;

use App\YouTubeSync\ServiceIterator;
use Exception;
use Google_Client;
use Google_Service_YouTube;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ServiceException extends Exception
{
}

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
     * The Google API client.
     *
     * @var \Google_Client;
     */
    public $client;

    /**
     * The Google API service.
     *
     * @var \Google_Service;
     */
    public $service;

    /**
     * Create the service manager.
     *
     * @param  string  $apiKey  the Google API key
     */
    public function __construct(string $apiKey)
    {
        $this->client = $client = new Google_Client();
        $client->setDeveloperKey($apiKey);
        $this->service = new Google_Service_YouTube($client);
    }

    /**
     * Return a generator of video details for for all the videos of a YouTube
     * playlist.
     *
     * This performs two sets of requests: the first to get the snippet part
     * for each video, and the second to get contentDetails, recordingDetails
     * and status parts.
     *
     * Each set of requests is paged on $this->pageSize.
     *
     * @param  int  $playlistId
     * @return \Generator
     */
    function getPlaylistVideos($playlistId)
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
                        throw new ServiceException('Playlist item does contain video ID: ' .
                            $video->id);
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
                    $basicVideo->_checked = true;
                } else {
                    throw new ServiceException('Unexpectedly received details for video: ' .
                        $video->id);
                }
                yield $video;
            }

            // Ensure an additional check to make sure we got each video...
            if($videos->isNotEmpty()) {
                Log::error('Did not receive details for video(s): ' .
                    $videos->keys()->join(', '));
            }
        }
    }

    /**
     * Return an iterator for a service request.
     *
     * @param  string  $property
     * @param  string  $method
     * @param  string  $part
     * @param  array  $params
     * @return  App\YouTubeSync\ServiceIterator
     */
    protected function getIterator(string $property, string $method,
                                   string $part, $callerParams = [])
    {
        return new ServiceIterator($this->pageSize, function($iteratorParams)
                                   use ($property, $method, $part, $callerParams) {
             $params = array_merge($callerParams, $iteratorParams);
             Log::info('YouTube API Request', [$property, $method, $part, $params]);
             return $this->service->{$property}->{$method}($part, $params);
        });
    }
}
