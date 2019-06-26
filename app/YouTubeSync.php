<?php

namespace App;

use App\Models\Author;
use App\Models\Language;
use App\Models\Talk;
use App\Util;
use App\YouTubeSync\Comparator;
use App\YouTubeSync\ServiceWrapper;
use ArrayIterator;
use Carbon\Carbon;
use Generator;
use Google_Client;
use Google_Model;
use Google_Service_YouTube;
use stdClass;

/**
 * YouTube Synchronization
 *
 * @see docs/youtube-sync.md
 * @see https://developers.google.com/youtube/v3/guides/auth/server-side-web-apps
 */
class YouTubeSync
{
    /**
     * The YouTUbe channel ID.
     *
     * @var string
     */
    protected $string;

    /**
     * The service wrapper.
     *
     * @var App/YouTubeSync/ServiceWrapper
     */
    protected $serviceWrapper;

    /**
     * Create the synchronization object.
     *
     * @param string                         $channelId
     * @param App\YouTubeSync\ServiceWrapper $serviceWrapper
     */
    public function __construct(string $channelId,
                                ServiceWrapper $serviceWrapper)
    {
        $this->channelId = $channelId;
        $this->serviceWrapper = $serviceWrapper;
    }

    /**
     * Return a YouTubeSync object with appropriate defaults.
     *
     * @param string $channelId
     *               The YouTube channel ID
     * @param string $clientId
     *               The Google OAuth client ID
     * @param string $clientSecret
     *               The Google OAuth client secret
     * @param string $redirectUrl
     *               Where to redirect browsers after authenticating with Google
     * @return App\YouTubeSync
     */
    public static function create(string $channelId, string $clientId,
                                  string $clientSecret, string $redirectUrl)
                                        : YouTubeSync
    {
        $client = new Google_Client;
        $client->setClientId($clientId);
        $client->setClientSecret($clientSecret);
        $client->addScope(Google_Service_YouTube::YOUTUBE_READONLY);
        $client->setRedirectUri($redirectUrl);
        $client->setAccessType('offline');
        $client->setIncludeGrantedScopes(true);
        $service = new Google_Service_YouTube($client);
        $serviceWrapper = new ServiceWrapper($service);
        return new static($channelId, $serviceWrapper);
    }

    /**
     * Return the YouTube channel ID.
     *
     * @return string
     */
    public function getChannelId() : string
    {
        return $this->channelId;
    }

    /**
     * Return the main "uploads" playlist for the channel.
     *
     * @return string
     */
    public function getChannelPlaylistId() : string
    {
        return ServiceWrapper::convertChannelPlaylistId($this->channelId);
    }

    /**
     * Return the Google authentication client.
     *
     * @return Google_Client
     */
    public function getClient() : Google_Client
    {
        return $this->serviceWrapper->getClient();
    }

    /**
     * Return the Google YouTube API service.
     *
     * @return ServiceWrapper
     */
    public function getService() : Google_Service_YouTube
    {
        return $this->serviceWrapper->getService();
    }

    /**
     * Return the service wrapper.
     *
     * @return ServiceWrapper
     */
    public function getServiceWrapper() : ServiceWrapper
    {
        return $this->serviceWrapper;
    }

    /*****************************************/

    public function printAll() : void
    {
        $part = 'snippet';
        $videos = $this->serviceWrapper->getChannelVideos($part, $this->channelId);
        foreach ($videos as $video) {
            print("{$video->id} {$video->snippet->title}\n");
        }
    }

    public function compareOne() : void
    {
        $part = 'contentDetails,recordingDetails,snippet,status';
        $videos = $this->serviceWrapper->getChannelVideos($part, $this->channelId);
        do {
            $video = $videos->current();
            $talk = Talk::where('youtube_id', $video->id)->first();
            $videos->next();
        } while (!$talk);

        $yt = $this->parseVideo($video);
        $this->compare($talk, $yt);
    }

    public function queueCreateUnassociatedTalks() : void
    {
        $part = 'contentDetails,recordingDetails,snippet,status';
        $videos = $this->serviceWrapper->getUnassociatedChannelVideos($part, $this->channelId);
        foreach ($videos as $video) {
            print("TO BE CREATED:\n"); // TODO
            print("  {$video->id} {$video->snippet->title}\n");
            // $this->queueCreateTalkFromVideo($video);
        }
    }

    public function createOne() : void
    {
        $part = 'contentDetails,recordingDetails,snippet,status';
        $videos = $this->serviceWrapper->getChannelVideos($part, $this->channelId);
        do {
            $video = $videos->current();
            $talk = Talk::where('youtube_id', $video->id)->first();
            $videos->next();
        } while ($talk);

        $yt = $this->parseVideo($video);
        print("Creating talk from video {$yt->youtube_id}\n");
        $talk = Talk::create([
            'title_en' => $yt->title_en,
            'title_th' => $yt->title_th,
            'language_id' => $yt->language_id ?? Language::english()->id,
            'author_id' => $yt->author_id ?? Author::where('title_en', 'Abhayagiri Sangha')->firstOrFail()->id,
            'description_en' => $yt->description_en,
            'description_th' => $yt->description_th,
            'youtube_id' => $yt->youtube_id,
            'recorded_on' => $yt->recorded_on ?? Carbon::parse($video->snippet->publishedAt ?? 'now'),
            'posted_at' => Carbon::now(),
        ]);
        print("Created talk {$talk->id}\n");

        $this->compare($talk, $yt);
    }

    /*****************************************/

    protected function compare(Talk $talk, stdClass $yt) : void
    {
        $compare = function($talk, $yt, $name) {
            $equals = $talk->$name == $yt->$name ? '==' : '!=';
            print("\$talk->$name $equals \$yt->$name\n");
            if ($equals === '!=') {
                print("  \$talk->$name = " . var_export($talk->$name, true) . "\n");
                print("  \$yt->$name = " . var_export($yt->$name, true) . "\n");
            }
        };

        print("Comparing talk {$talk->id} <-> video {$yt->youtube_id}\n");
        $compare($talk, $yt, 'youtube_id');
        $compare($talk, $yt, 'youtube_normalized_title');
        $compare($talk, $yt, 'title_en');
        $compare($talk, $yt, 'title_th');
        $compare($talk, $yt, 'language_id');
        $compare($talk, $yt, 'author_id');
        $compare($talk, $yt, 'description_en');
        $compare($talk, $yt, 'description_th');
        $compare($talk, $yt, 'recorded_on');
    }

    protected function parseVideo(Google_Model $video) : stdClass
    {
        $yt = new stdClass;
        $yt->youtube_id = $video->id ?? null;
        $yt->youtube_normalized_title = $video->snippet->title ?? '';
        $titleAuthor = Comparator::extractTitleAndAuthorFromYouTubeTitle($yt->youtube_normalized_title);
        $yt->title_en = $titleAuthor->title;
        $yt->title_th = $video->snippet->localized->th->title ?? null;
        $yt->language_code = $video->snippet->defaultAudioLanguage ?? null;
        $yt->language = Language::where('code', $yt->language_code)->first();
        $yt->language_id = $yt->language->id ?? null;
        $yt->author = $titleAuthor->author;
        $yt->author_id = $yt->author->id ?? null;
        // TODO html parse to markdown ?
        // TODO remove no-sync ?
        $yt->description_en = $video->snippet->description ?? null;
        $yt->description_th = $video->snippet->localized->th->description ?? null;
        $yt->recorded_on = isset($video->recordingDetails->recordingDate) ?
            Carbon::parse($video->recordingDetails->recordingDate) : null;
        // The following aren't compared or used for now...
        $yt->posted_at = Carbon::parse($video->snippet->publishedAt);
        $yt->duration = Util::iso8601DurationToSeconds($video->contentDetails->duration);
        return $yt;
    }
}
