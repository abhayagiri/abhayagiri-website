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
use stdClass;

/**
 * YouTube Synchronization
 *
 * @see docs/youtube-sync.md
 */
class YouTubeSync
{
    protected static function getService($apiKey)
    {
        $client = new Google_Client;
        $client->setDeveloperKey($apiKey);
        $googleService = new Google_Service_YouTube($client);
        return new Service($googleService);
    }

    public static function listAll($apiKey, $playlistId)
    {
        $service = static::getService($apiKey);
        foreach ($service->getPlaylistVideos($playlistId) as $video) {
            print("{$video->id} {$video->snippet->title}\n");
        }
    }

    public static function compareOne($apiKey, $playlistId)
    {
        $service = static::getService($apiKey);
        $iterator = $service->getPlaylistVideos($playlistId);

        do {
            $video = $iterator->current();
            $talk = Talk::where('youtube_id', $video->id)->first();
            $iterator->next();
        } while (!$talk);

        $yt = static::parseVideo($video);

        static::compare($talk, $yt);
    }

    public static function createOne($apiKey, $playlistId)
    {
        $service = static::getService($apiKey);
        $iterator = $service->getPlaylistVideos($playlistId);

        do {
            $video = $iterator->current();
            $talk = Talk::where('youtube_id', $video->id)->first();
            $iterator->next();
        } while ($talk);

        $yt = static::parseVideo($video);

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

        static::compare($talk, $yt);
    }

    protected static function compare($talk, $yt)
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

    protected static function parseVideo($video)
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
