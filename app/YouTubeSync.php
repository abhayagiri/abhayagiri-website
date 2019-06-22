<?php

namespace App;

use App\Models\Author;
use App\Models\Language;
use App\Models\Talk;
use App\Util;
use App\YouTubeSync\Comparator;
use App\YouTubeSync\Service;
use Carbon\Carbon;
use stdClass;

class YouTubeSync
{
    public static function example($apiKey, $playlistId)
    {
        $service = new Service($apiKey);
        $iterator = $service->getPlaylistVideos($playlistId);
        $video = $iterator->current();
        print("{$video->id} {$video->snippet->title}\n");

        $talk = Talk::where('youtube_id', $video->id)->firstOrFail();
        $en = Language::where('code', 'en')->firstOrFail();

        $yt = new stdClass;
        $titleAuthor = Comparator::extractTitleAndAuthorFromYouTubeTitle($video->snippet->title);
        $yt->title_en = $titleAuthor->title;
        $yt->author = $titleAuthor->author;
        // TODO html parse to markdown ?
        $yt->description_en = $video->snippet->description;
        $yt->youtube_id = $video->id;
        var_dump($video);
        if (isset($video->recordingDetails->recordingDate)) {
            $yt->recorded_on = Carbon::parse($video->recordingDetails->recordingDate)->toDate();
        }
        $yt->posted_at = Carbon::parse($video->snippet->publishedAt);
        $yt->duration = Util::iso8601DurationToSeconds($video->contentDetails->duration);

        // Comparisons
        $talk->youtube_normalized_title === $video->snippet->title;
        $talk->title_en === $yt->title_en;
        $talk->author == $yt->author;
        $talk->description_en ?? '' === $yt->description_en;
        $talk->youtube_id === $yt->youtube_id;
        $talk->recorded_on == $yt->recorded_on;
        // should not be compared, but possibly?
        $talk->posted_at == $yt->posted_at;

        Talk::create([
            'language_id' => $en->id, // TODO, how to determine language?
            'title_en' => $yt->title_en,
            'author_id' => $yt->author->id,
            'description_en' => $yt->description_en,
            'youtube_id' => $yt->youtube_id,
            'recorded_on' => $yt->recorded_on,
            'posted_at' => Carbon::now(),
        ]);
    }
}
