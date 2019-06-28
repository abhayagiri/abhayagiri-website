<?php

namespace App;

use App\Models\Author;
use App\Models\Language;
use App\Models\Playlist;
use App\Models\PlaylistGroup;
use App\Models\SyncTask;
use App\Models\Talk;
use App\Util;
use App\YouTubeSync\Comparator;
use App\YouTubeSync\ServiceWrapper;
use ArrayIterator;
use Carbon\Carbon;
use Closure;
use Generator;
use Google_Client;
use Google_Model;
use Google_Service_YouTube;
use Google_Service_YouTube_Playlist;
use Google_Service_YouTube_Video;
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

    public function queueCreateUnassociatedPlaylists()
    {
        $playlists = $this->serviceWrapper->getChannelPlaylists(
            'snippet', $this->channelId);
        foreach ($playlists as $playlist) {
            // TODO check for no-sync
            if (false) {
                continue;
            }
            $key = 'createPlaylistFromYouTubePlaylist:' . $playlist->id;
            $syncTask = SyncTask::findOrCreateWithLock($key);
            if (!$syncTask) {
                // TODO maybe this should be another sync task?
                print('Could not get SyncTask with key ' . $key);
                continue;
            }
            try {
                $playlist = $this->simplifyYouTubePlaylist($playlist);
                $syncTask->extra = [
                    'youTubePlaylistId' => $playlist->id,
                    'lastChecked' => Carbon::now(),
                    'thumbnailUrl' => $playlist->thumbnailsDefaultUrl,
                    'title' => $playlist->title,
                ];
                $syncTask->save();
                $syncTask->addLog('Queued create Playlist from YouTube Playlist',
                                  $playlist);
            } finally {
                $syncTask->releaseLock();
            }
        }
    }

    public function queueCreateUnassociatedTalks() : void
    {
        $videos = $this->serviceWrapper->getUnassociatedVideos(
            $this->channelId);
        foreach ($videos as $video) {
            // TODO check for no-sync
            if (false) {
                continue;
            }
            $key = 'createTalkFromYouTubeVideo:' . $video->id;
            $syncTask = SyncTask::findOrCreateWithLock($key);
            if (!$syncTask) {
                // TODO maybe this should be another sync task?
                print('Could not get SyncTask with key ' . $key);
                continue;
            }
            try {
                $video = $this->simplifyYouTubeVideo($video);
                $syncTask->extra = [
                    'youTubeVideoId' => $video->id,
                    'lastChecked' => Carbon::now(),
                    'thumbnailUrl' => $video->thumbnailsDefaultUrl,
                    'title' => $video->title,
                ];
                $syncTask->save();
                $syncTask->addLog('Queued create Talk from YouTube Video',
                                  $video);
            } finally {
                $syncTask->releaseLock();
            }
        }
    }

    public function popCreatePlaylist() : void
    {
        $this->withSyncTask('createPlaylist%', function($syncTask) {
            $youTubePlaylistId = $syncTask->extra['youTubePlaylistId'] ?? null;
            if (!$youTubePlaylistId) {
                $syncTask->addLog('ERROR: Could not find YouTube Playlist ' .
                                  'ID from SyncTask', $syncTask->id);
                return true;
            }
            $part = 'snippet';
            $youTubePlaylist =
                $this->serviceWrapper->getPlaylist($part, $youTubePlaylistId);
            if (!$youTubePlaylist) {
                $syncTask->addLog('ERROR: Could not get YouTube Playlist',
                                  $youTubePlaylistId);
                return false;
            }
            $playlist =
                $this->createPlaylistFromYouTubePlaylist($youTubePlaylist);
            if ($playlist->exists) {
                $logMessage = 'Created Playlist from YouTube Playlist';
            } else {
                $logMessage = 'ERROR: Could not create Playlist from ' .
                              'YouTube Playlist';
            }
            $syncTask->addLog($logMessage, [
                'playlist' => $playlist->getAttributes(),
                'youTubePlayList' => $this->simplifyYouTubePlaylist($youTubePlaylist),
            ]);
            $syncTask->addLog('!');
            return $playlist->exists;
        });
    }

    public function popCreateTalk() : void
    {
        $this->withSyncTask('createTalk%', function($syncTask) {
            $youTubeVideoId = $syncTask->extra['youTubeVideoId'] ?? null;
            if (!$youTubeVideoId) {
                $syncTask->addLog('ERROR: Could not find YouTube Video ID ' .
                                  'from SyncTask', $syncTask->id);
                return true;
            }
            $part = 'contentDetails,recordingDetails,snippet,status';
            $youTubeVideo =
                $this->serviceWrapper->getVideo($part, $youTubeVideoId);
            if (!$youTubeVideo) {
                $syncTask->addLog('ERROR: Could not get YouTube Video',
                                  $youTubeVideoId);
                return false;
            }
            $talk = $this->createTalkFromYouTubeVideo($youTubeVideo);
            if ($talk->exists) {
                $logMessage = 'Created Talk from YouTube Video';
            } else {
                $logMessage = 'ERROR: Could not create Talk from YouTube ' .
                              'Video';
            }
            $syncTask->addLog($logMessage, [
                'talk' => $talk->getAttributes(),
                'youTubeVideo' => $this->simplifyYouTubeVideo($youTubeVideo),
            ]);
            return $talk->exists;
        });
    }

    protected function createPlaylistFromYouTubePlaylist(
            Google_Service_YouTube_Playlist $youTubePlaylist) : Playlist
    {
        $youTubePlaylist = $this->simplifyYouTubePlaylist($youTubePlaylist);
        // TODO descriptions need to be cleaned:
        // Transform \n -> \r\n
        // Remove no-sync at end
        // trim?
        $descriptionEn = $youTubePlaylist->description;
        $descriptionTh = $youTubePlaylist->localizedThDescription;

        return Playlist::create([
            'group_id' => PlaylistGroup::first()->id, // TODO brittle
            'title_en' => $youTubePlaylist->title,
            'title_th' => $youTubePlaylist->localizedThTitle,
            'description_en' => $descriptionEn,
            'description_th' => $descriptionTh,
            'youtube_playlist_id' => $youTubePlaylist->id,
        ]);
    }

    protected function createTalkFromYouTubeVideo(
            Google_Service_YouTube_Video $video) : Talk
    {
        $video = $this->simplifyYouTubeVideo($video);
        $titleAuthor = Comparator::extractTitleAndAuthorFromYouTubeTitle(
            $video->title);
        $languageId = (Language::where('code', $video->defaultAudioLanguage)
                               ->first() ?? Language::english())->id;
        $authorId = $titleAuthor->author->id ?? Author::sangha()->id;
        // TODO descriptions need to be cleaned:
        // Transform \n -> \r\n
        // Remove no-sync at end
        // trim?
        $descriptionEn = $video->description;
        $descriptionTh = $video->localizedThDescription;
        $recordedOn = Carbon::parse(
            $video->recordingDate ??
            $video->snippet->publishedAt ??
            'now'
        )->toDate();

        // The following aren't used for now...
        $duration = Util::iso8601DurationToSeconds($video->duration);

        $talk = Talk::create([
            'title_en' => $titleAuthor->title,
            'title_th' => $video->localizedThTitle,
            'language_id' => $languageId,
            'author_id' => $authorId,
            'description_en' => $descriptionEn,
            'description_th' => $descriptionTh,
            'youtube_video_id' => $video->id,
            'recorded_on' => $recordedOn,
            'posted_at' => Carbon::now(),
        ]);
        return $talk;
    }

    /**
     * Return a simplified and flattened YouTube playlist model.
     *
     * @param Google_Service_YouTube_Playlist $playlist
     * @return stdClass
     */
    protected function simplifyYouTubePlaylist(
            Google_Service_YouTube_Playlist $playlist) : stdClass
    {
        $sp = new stdClass;
        $sp->id = $playlist->id ?? null;
        $sp->title = $playlist->snippet->title ?? null;
        $sp->description = $playlist->snippet->description ?? null;
        $sp->localizedThTitle =
            $playlist->snippet->localized->th->title ?? null;
        $sp->localizedThDescription =
            $playlist->snippet->localized->th->description ?? null;
        $sp->publishedAt = $playlist->snippet->publishedAt ?? null;
        $sp->thumbnailsDefaultUrl =
            $playlist->snippet->thumbnails->default->url ?? null;
        return $sp;
    }

    /**
     * Return a simplified and flattened YouTube video model.
     *
     * @param Google_Model $video
     * @return stdClass
     */
    protected function simplifyYouTubeVideo(Google_Model $video) : stdClass
    {
        $sv = new stdClass;
        $sv->id = $video->id ?? null;
        $sv->title = $video->snippet->title ?? null;
        $sv->description = $video->snippet->description ?? null;
        $sv->localizedThTitle =
            $video->snippet->localized->th->title ?? null;
        $sv->localizedThDescription =
            $video->snippet->localized->th->description ?? null;
        $sv->defaultAudioLanguage =
            $video->snippet->defaultAudioLanguage ?? null;
        $sv->recordingDate = $video->recordingDetails->recordingDate ?? null;
        $sv->publishedAt = $video->snippet->publishedAt ?? null;
        $sv->thumbnailsDefaultUrl =
            $video->snippet->thumbnails->default->url ?? null;
        // The following aren't used for now...
        $sv->duration = $video->contentDetails->duration ?? null;
        return $sv;
    }

    public function withSyncTask(string $likeKey, Closure $handler) : void
    {
        $syncTask = SyncTask::unlocked()->queued()
                            ->where('key', 'like', $likeKey)->first();
        if ($syncTask) {
            print('Running SyncTask ' . $syncTask->id . "...\n");
            $syncTask->runWithLock(function ($syncTask) use ($handler) {
                $syncTask->addLog('Start task handler');
                $result = false;
                try {
                    $result = $handler($syncTask);
                } catch (\Exception $e) {
                    $syncTask->addLog('ERROR: Got exception . ' .
                                      $e->getMessage());
                    throw $e;
                } finally {
                    $syncTask->addLog('End task handler');
                }
                return $result;
            });
        } else {
            print("Could not get any SyncTask for $likeKey...\n");
        }
    }
}
