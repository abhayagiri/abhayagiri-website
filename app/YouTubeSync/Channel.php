<?php

namespace App\YouTubeSync;

use App\YouTubeSync\ResponseIterator;
use Generator;
use Google_Client;
use Google_Collection;
use Google_Model;
use Google_Service_YouTube;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

        $part = $part ?: 'contentDetails,recordingDetails,snippet,status';



    /**
     * Return a generator of playlist snippets for all the playlists in the
     * YouTube channel.
     *
     * @return Generator
     */
    public function getChannelPlaylistSnippets() : Generator
    {
        $iterator = $this->iterateResponse(
            'playlists', 'listPlaylists',
            'snippet', ['channelId' => $this->channelId]);
        foreach ($iterator as $playlist) {
            yield $playlist;
        }
    }

    /**
     * Return a generator of videos snippets for all videos in the YouTube
     * channel.
     *
     * @return Generator
     */
    public function getChannelVideoSnippets() : Generator
    {
        return $this->getPlaylistVideoSnippets($this->getChannelPlaylistId());
    }

    /**
     * Return a generator of videos snippets for all the videos of a YouTube
     * playlist.
     *
     * @param string $playlistId
     * @return Generator
     */
    public function getPlaylistVideoSnippets($playlistId) : Generator
    {
        $iterator = $this->iterateRequest(
            'playlistItems', 'listPlaylistItems',
            'snippet', ['playlistId' => $playlistId]);
        foreach ($iterator as $video) {
            $id = $video->snippet->resourceId->videoId ?? null;
            if ($id) {
                yield $video;
            } else {
                Log::error('Playlist item does contain video id: ' .
                           $video->id);
            }
        }
    }

    /**
     * Return a generator of video details for all the videos of a YouTube
     * playlist.
     *
     * This performs two sets of requests: the first to get the snippet part
     * for each video, and the second to get contentDetails, recordingDetails
     * and status parts.
     *
     * @param string $playlistId
     * @return Generator
     */
    function getPlaylistVideoDetails($playlistId) : Generator
    {
        $basicIterator = $this->iterateRequest(
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
                        Log::error('Playlist item does contain video id: ' .
                                   $video->id);
                    }
                    $basicIterator->next();
                } else {
                    break;
                }
            }

            $detailIterator = $this->iterateRequest(
                'videos', 'listVideos',
                'contentDetails,recordingDetails,status',
                ['id' => $videos->keys()->join(',')]);

            foreach ($detailIterator as $video) {
                if ($basicVideo = $videos->pull($video->id)) {
                    $video->snippet = $basicVideo->snippet;
                    yield $video;
                } else {
                    Log::warning('Unexpectedly received details for video: ' .
                                 $video->id);
                }
            }

            // Perform an additional check to see if we got each video...
            if($videos->isNotEmpty()) {
                Log::warning('Did not receive details for video(s): ' .
                             $videos->keys()->join(', '));
            }
        }
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
     * Return full details for one video.
     *
     * @param string $videoId
     * @return Google_Model|null
     */
    public function getVideoDetail(string $videoId) : ?Google_Model
    {
        $videos = iterator_to_array($this->getVideoDetails([$videoId]));
        if (count($videos) === 1) {
            return $videos[0];
        } else {
            return null;
        }
    }

    /**
     * Return full details for one or more videos.
     *
     * @param iterable $videoIds
     *                 One or more video IDs.
     * @return App\YouTubeSync\ResponseIterator
     */
    public function getVideoDetails(iterable $videoIds) : ResponseIterator
    {
        $part = 'contentDetails,recordingDetails,snippet,status';
        $params = ['id' => (new Collection($videoIds))->join(',')];
        return $this->iterateRequest('videos', 'listVideos', $part, $params);
    }

    /**
     * Return a generator of video IDs for YouTube channel videos unassociated
     * with talks on the website.
     *
     * @return Generator
     */
    protected function getUnassociatedChannelVideoIds() : Generator
    {
        $iterator = $this->inBatches($this->getChannelVideoSnippets(),
                                     $this->pageSize);
        foreach ($iterator as $batch) {
            $videoIds = $batch->map(function($video) {
                return $video->snippet->resourceId->videoId;
            });
            $videoIdsToBeAdded = $videoIds->diff(
                Talk::withTrashed()->whereIn('youtube_id', $videoIds)
                                   ->pluck('youtube_id')->get());
            foreach ($videoIdsToBeAdded as $videoId) {
                yield $videoId;
            }
        }
    }

    /**
     * Return a generator of video details for YouTube channel videos
     * unassociated with talks on the website.
     *
     * @return Generator
     */
    protected function getUnassociatedChannelVideos() : Generator
    {
        $iterator = $this->inBatches($this->getUnassociatedChannelVideoIds,
                                     $this->pageSize);
        foreach ($iterator as $videoIdBatch) {
            return $this->getVideoDetails($videoIdBatch);
        }
    }

    /**
     * Return a generator of batches of items with a particular size for a generator.
     *
     * @param Iterator $iterator
     * @param int $size
     * @return Generator
     */
    protected function inBatches(Iterator $iterator, int $size) : Generator
    {
        do {
            $batch = new Collection;
            while ($iterator->valid()) {
                $video = $iterator->current();
                $batch->add($video);
                $iterator->next();
                if ($batch->count() >= $size) {
                    break;
                }
            }
            yield $batch;
        } while ($iterator->valid());
    }

    /**
     * Return an iterator for a YouTube API service request.
     *
     * @param string $property
     * @param string $method
     * @param string $part
     * @param array  $callerParams
     *               These are merged with the iterator params.
     * @return App\YouTubeSync\ResponseIterator
     *
     * @see YouTubeSync::request()
     */
    protected function iterateRequest(string $property, string $method,
                                      string $part, array $callerParams)
                                      : ResponseIterator
    {
        return new ResponseIterator($this->pageSize, function($iteratorParams)
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
        dump(['YouTube API', $property, $method, $part, $params]); // TODO DEBUGGING
        Log::info('YouTube API Request', [$property, $method, $part, $params]);
        return $this->service->{$property}->{$method}($part, $params);
    }
}
