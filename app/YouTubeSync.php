<?php

namespace App;

use App\Models\Author;
use App\Models\Language;
use App\Models\Talk;
use App\Util;
use App\YouTubeSync\Comparator;
use App\YouTubeSync\Service;
use Carbon\Carbon;
use Google_Client;
use Google_Service;
use Google_Service_YouTube;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use stdClass;

/**
 * YouTube Synchronization
 *
 * @see docs/youtube-sync.md
 */
class YouTubeSync
{
    /**
     * The YouTube channel ID.
     *
     * @var string
     */
    public $channelId;

    /**
     * The Google client.
     *
     * @var Google_Client
     */
    public $client;

    /**
     * The synchronization service.
     *
     * @var App/YouTubeSync/Service
     */
    public $service;

    /**
     * Create the synchronizor object.
     *
     * @param string         $channelId (Optional)
     * @param Google_Client  $client    (Optional)
     * @param Google_Service $service   (Optional)
     */
    public function __construct(string $channelId = null,
                                Google_Client $client = null,
                                Google_Service $service = null)
    {
        $channelId = $channelId ?? Config::get('abhayagiri.youtube_channel_id');
        $client = $client ?? $this->getGoogleClient();
        $service = $service ?? new Google_Service_YouTube($client);
        $this->channelId = $channelId;
        $this->client = $client;
        $this->service = new Service($service);
    }

    /**
     * Return a Google_Client configured for offline use.
     *
     * @return Google_Client
     *
     * @see https://developers.google.com/youtube/v3/guides/auth/server-side-web-apps
     */
    protected function getGoogleClient() : Google_Client
    {
        $client = new Google_Client;
        $client->setClientId(Config::get('abhayagiri.youtube_oauth_client_id'));
        $client->setClientSecret(Config::get('abhayagiri.youtube_oauth_client_secret'));
        $client->addScope(Google_Service_YouTube::YOUTUBE_READONLY);
        $client->setRedirectUri(URL::to('/admin/youtube-oauth'));
        $client->setAccessType('offline');
        $client->setIncludeGrantedScopes(true);
        return $client;
    }

    public function listAll() : void
    {
        $playlistId = $this->getChannelPlaylistId();
        foreach ($this->service->getPlaylistVideos($playlistId) as $video) {
            print("{$video->id} {$video->snippet->title}\n");
        }
    }

    public function compareOne() : void
    {
        $playlistId = $this->getChannelPlaylistId();
        $iterator = $this->service->getPlaylistVideos($playlistId);

        do {
            $video = $iterator->current();
            $talk = Talk::where('youtube_id', $video->id)->first();
            $iterator->next();
        } while (!$talk);

        $yt = $this->parseVideo($video);

        $this->compare($talk, $yt);
    }

    public function createOne() : void
    {
        $playlistId = $this->getChannelPlaylistId();
        $iterator = $this->service->getPlaylistVideos($playlistId);

        do {
            $video = $iterator->current();
            $talk = Talk::where('youtube_id', $video->id)->first();
            $iterator->next();
        } while ($talk);

        $yt = $this->parseVideo($video);

        print("Creating talk from video {$yt->youtube_id}\n");
        $talk = Talk::create([
            'title_en' => $yt->title_en,
            'title_th' => $yt->title_th,
            'language_id' => $yt->language_id ?? Language::where('code', 'en')->firstOrFail()->id,
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

    protected function parseVideo(string $video) : stdClass
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

    /**
     * Returns the main "Uploads" playlist for the channel.
     *
     * @return string
     */
    public function getChannelPlaylistId() : string
    {
        // Replace the second character with a 'U' to get the "Uploads"
        // playlist..
        return $this->channelId[0] . 'U' . substr($this->channelId, 2);
    }
}
