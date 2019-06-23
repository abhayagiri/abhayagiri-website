<?php

namespace App\Models;

use App\Legacy;
use Backpack\CRUD\CrudTrait;
use App\Facades\Id3WriterHelper;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\TalkObserversTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Talk extends Model
{
    use CrudTrait;
    use RevisionableTrait;
    use SoftDeletes;
    use Traits\AutoSlugTrait;
    use Traits\LocalDateTimeTrait;
    use Traits\ImageCrudColumnTrait;
    use Traits\ImagePathTrait;
    use Traits\MarkdownHtmlTrait;
    use Traits\MediaPathTrait;
    use Traits\PostedAtTrait;
    use TalkObserversTrait;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'slug', 'deleted_at', 'created_at', 'updated_at'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['recorded_on', 'posted_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'check_translation' => 'boolean',
        'hide_from_latest' => 'boolean',
        'draft' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'description_html_en',
        'description_html_th',
        'path',
        'image_url',
        'media_url',
        'url_title',
        'body',
        'mp3',
        'youtube_url',
        'download_filename',
    ];

    /**
     * The attributes that should not be revisioned.
     *
     * @var array
     */
    protected $dontKeepRevisionOf = [
        'slug', 'check_translation', 'deleted_at',
    ];

    /**
     * The attribute or method that derives the slug.
     *
     * @var string
     */
    protected $slugFrom = 'title_en';

    /**
     * Override to store the creation as a revision
     *
     * @var bool
     */
    protected $revisionCreationsEnabled = true;

    /**********
     * Scopes *
     **********/

    public function scopeLatestVisible($query)
    {
        return $query
            ->where('talks.hide_from_latest', false);
    }

    /**
     * Scope a query include the latest talks from a playlistGroup.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \App\Models\PlaylistGroup $playlistGroup
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLatestTalks($query, $playlistGroup)
    {
        return $query
            ->distinct()
            ->select('talks.*')
            ->join('playlist_talk', 'playlist_talk.talk_id', '=', 'talks.id')
            ->join('playlists', 'playlist_talk.playlist_id', '=', 'playlists.id')
            ->where('playlists.group_id', '=', $playlistGroup->id)
            ->public()
            ->latestVisible()
            ->latest();
    }

    /*****************
     * Relationships *
     *****************/

    public function language()
    {
        return $this->belongsTo('App\Models\Language');
    }

    public function author()
    {
        return $this->belongsTo('App\Models\Author');
    }

    public function playlists()
    {
        return $this->belongsToMany('App\Models\Playlist');
    }

    public function subjects()
    {
        return $this->belongsToMany('App\Models\Subject');
    }

    public function oldSubjects()
    {
        return new TalkSubjectRelation($this);
    }

    /**************************
     * Accessors and Mutators *
     **************************/

    public function getPathAttribute()
    {
        return '/talks/' . $this->getKey() . '-' . $this->getAttribute('slug');
    }

    public function getUrlTitleAttribute()
    {
        return $this->getAttribute('slug');
    }

    public function getBodyAttribute()
    {
        return $this->getDescriptionHtmlEnAttribute();
    }

    public function getMediaUrlAttribute()
    {
        return $this->getMediaPathFrom('media_path');
    }

    public function setMediaPathAttribute($value)
    {
        $this->setMediaPathAttributeTo('media_path', $value);
    }

    public function getDownloadFilenameAttribute()
    {
        $path = $this->getAttribute('media_path');
        $title = $this->title_en;
        $date = $this->recorded_on;

        if (! $path || ! $title || ! $date) {
            return null;
        }
        $ext = preg_replace('/^.*?(?:\.([^.]{1,4}))?$/', '\1', $path);
        $ext = $ext === '' ? 'mp3' : $ext;
        $filename = $date->format('Y-m-d') . ' ' . $title . '.' . $ext;
        // Remove invalid Windows, Linux, OS X characters
        $filename = preg_replace('_[\x00-\x1f<>:"/\\\\|?*]_', '', $filename);
        $filename = trim(preg_replace('/ {2,}/', ' ', $filename));

        return $filename;
    }

    public function getMp3Attribute()
    {
        $mediaPath = $this->getAttribute('media_path');

        if (! $mediaPath) {
            return null;
        } elseif (starts_with($mediaPath, 'audio/')) {
            return preg_replace('_^audio/_', '', $mediaPath);
        } else {
            return '../' . $mediaPath;
        }
    }

    public function setYoutubeIdAttribute($youtube_id)
    {
        if (strpos($youtube_id, 'youtu') !== false) {
            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $youtube_id, $match);
            $youtube_id = $match[1] ?? $youtube_id;
        }

        $this->attributes['youtube_id'] = $youtube_id;
    }

    public function getYoutubeUrlAttribute()
    {
        $youtubeId = $this->getAttribute('youtube_id');

        return $youtubeId ? ('https://youtu.be/' . $youtubeId) : null;
    }

    public function getYoutubeNormalizedTitleAttribute()
    {
        return "{$this->title_en} | {$this->author->title_en}";
    }

    /**********
     * Legacy *
     **********/

    public function toLegacyArray($language = 'English')
    {
        return [
            'id' => $this->id,
            'url_title' => $this->id . '-' . $this->slug,
            'title' => Legacy::getEnglishOrThai(
                $this->title_en,
                $this->title_th,
                $language
            ),
            'author' => Legacy::getAuthor($this->author, $language),
            'author_image_url' => $this->author->image_url,
            'body' => Legacy::getEnglishOrThai(
                $this->description_html_en,
                $this->description_html_th,
                $language
            ),
            'date' => $this->local_posted_at,
            'mp3' => $this->mp3,
            'media_url' => $this->media_url,
        ];
    }

    public static function getLegacyHomeTalk($language = 'English')
    {
        $mainPlaylistGroup = self::getLatestPlaylistGroup('main');

        return self::latestTalks($mainPlaylistGroup)
            ->first()
            ->toLegacyArray($language);
    }

    /*********
     * Other *
     *********/

    public function getPath($lng = 'en')
    {
        return ($lng === 'th' ? '/th' : '') . $this->getAttribute('path');
    }

    public function updateId3Tags()
    {
        $fullFileName = base_path('public/media/' . $this->media_path);

        if (File::exists($fullFileName) && File::extension($fullFileName) == 'mp3') {
            Id3WriterHelper::configureWriter($fullFileName, 'id3v2.3', true, false, 'UTF-8');
            Id3WriterHelper::setTag('title', $this->title_en);
            Id3WriterHelper::setTag('artist', optional($this->author)->title_en);
            Id3WriterHelper::setTag('year', optional($this->recorded_on)->year);
            Id3WriterHelper::writeTags();
        }
    }

    /**
     * Return the PlaylistGroup defined in settings
     * talks.latest.$key.playlist_group_id.
     *
     * @param string $key ('main'|'alt')
     *
     * @return \App\Models\PlaylistGroup
     */
    public static function getLatestPlaylistGroup($key)
    {
        $playlistGroup = PlaylistGroup::find(
            config('settings.talks.latest.' . $key . '.playlist_group_id')
        );

        if (! $playlistGroup) {
            $playlistGroup = PlaylistGroup::first();
        }

        return $playlistGroup;
    }

    /**
     * Return the count defined in settings talks.latest.$key.count.
     *
     * @param string $key ('main'|'alt')
     *
     * @return int
     */
    public static function getLatestCount($key)
    {
        return (int) config('settings.talks.latest.' . $key .
            '.count', 3);
    }

    /**
     * Return the settings defined in settings talks.latest.$key.*.
     *
     * @param string $key ('authors'|'playlists'|'subjects')
     *
     * @return int
     */
    public static function getLatestBunch($key)
    {
        $setting = function ($key) {
            return config('settings.talks.latest.' . $key);
        };
        $imageFile = $setting($key . '.image_file');
        $imagePath = $imageFile ? '/media/' . $imageFile : null;
        $descriptionEn = $setting($key . '.description_en');
        $descriptionTh = $setting($key . '.description_th');

        return [
            'imagePath' => $imagePath,
            'descriptionEn' => $descriptionEn,
            'descriptionTh' => $descriptionTh,
        ];
    }
}
