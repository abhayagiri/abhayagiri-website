<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Venturecraft\Revisionable\RevisionableTrait;

use App\Legacy;
use App\Models\Subject;

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
    protected $appends = ['description_html_en', 'description_html_th',
        'image_url', 'media_url', 'url_title', 'body', 'mp3', 'youtube_url'];

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

    /**********
     * Scopes *
     **********/

    public function scopeLatestVisible($query)
    {
        return $query
            ->where('talks.hide_from_latest', false);
    }

    /*****************
     * Relationships *
     *****************/

    public function language()
    {
        return $this->belongsTo('App\Models\Language');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\TalkType');
    }

    public function author()
    {
        return $this->belongsTo('App\Models\Author');
    }

    public function playlists()
    {
        return $this->belongsToMany('App\Models\Playlist');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag');
    }

    /**************************
     * Accessors and Mutators *
     **************************/

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
        return $this->getMediaUrlFrom('media_path');
    }

    public function setMediaPathAttribute($value)
    {
        $this->setMediaPathAttributeTo('media_path', $value);
    }

    public function getMp3Attribute()
    {
        $mediaPath = $this->getAttribute('media_path');
        if (!$mediaPath) {
            return null;
        } else if (starts_with($mediaPath, 'audio/')) {
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

    /**********
     * Legacy *
     **********/

    public function toLegacyArray($language = 'English')
    {
        return [
            'id' => $this->id,
            'url_title' => $this->id . '-' . $this->slug,
            'title' => Legacy::getEnglishOrThai(
                $this->title_en, $this->title_th, $language),
            'author' => Legacy::getAuthor($this->author, $language),
            'author_image_url' => $this->author->image_url,
            'body' => Legacy::getEnglishOrThai(
                $this->description_html_en,
                $this->description_html_th, $language),
            'date' => $this->local_posted_at,
            'mp3' => $this->mp3,
            'media_url' => $this->media_url,
        ];
    }

    public static function getLegacyHomeTalk($language = 'English')
    {
        return static::public()
            ->latestVisible()
            ->latest()
            ->first()
            ->toLegacyArray($language);
    }

    /*********
     * Other *
     *********/

    public function getSubjects()
    {
        $subjectIds = DB::table('tag_talk')
            ->join('subject_tag', 'tag_talk.tag_id', '=', 'subject_tag.tag_id')
            ->where('tag_talk.talk_id', '=', $this->id)
            ->pluck('subject_tag.subject_id');
        return Subject::whereIn('id', $subjectIds);
    }

    public function getPath($lng = 'en')
    {
        return ($lng === 'th' ? '/new/th' : '/new') .
            '/talks/' . $this->id . '-' . str_slug($this->title_en);
    }
}
