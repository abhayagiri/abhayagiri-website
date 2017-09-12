<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Venturecraft\Revisionable\RevisionableTrait;

use App\Models\Subject;

class Talk extends Model
{
    use CrudTrait;
    use RevisionableTrait;
    use SoftDeletes;
    use Traits\AutoSlugTrait;
    use Traits\DraftTrait;
    use Traits\DescriptionHtmlTrait;
    use Traits\LocalDateTimeTrait;
    use Traits\LocalPostedAtTrait;
    use Traits\ImageCrudColumnTrait;
    use Traits\ImagePathTrait;
    use Traits\MediaPathTrait;

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
        'image_url', 'media_url', 'url_title', 'body', 'mp3'];

    /**
     * The attributes that should not be revisioned.
     *
     * @var array
     */
    protected $dontKeepRevisionOf = [
        'slug', 'deleted_at',
    ];

    /*
     * Scopes.
     */

    public function scopeLatestVisible($query)
    {
        return $query
            ->where('talks.hide_from_latest', false);
    }

    public function scopeLatest($query)
    {
        return $query
            ->orderBy('talks.posted_at', 'desc');
    }

    /*
     * Relationships.
     */

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

    /*
     * Attribute accessors and mutators.
     */

    public function setTitleEnAttribute($value)
    {
        $this->setAutoSlugTo('title_en', $value);
    }

    public function getUrlTitleAttribute()
    {
        return array_get($this->attributes, 'slug');
    }

    public function getBodyAttribute()
    {
        return $this->getDescriptionHtmlEnAttribute();
    }

    public function getMediaUrlAttribute()
    {
        return $this->getMediaUrlFrom('media_path');
    }

    public function setMediaUrlAttribute($value)
    {
        $this->setMediaPathAttributeTo('media_path', $value);
    }

    public function getMp3Attribute()
    {
        $mediaPath = array_get($this->attributes, 'media_path');
        if (!$mediaPath) {
            return null;
        } else if (starts_with($mediaPath, 'audio/')) {
            return preg_replace('_^audio/_', '', $mediaPath);
        } else {
            return '../' . $mediaPath;
        }
    }

    /*
     * Other methods/properties.
     */

    public function getSubjects()
    {
        $subjectIds = DB::table('tag_talk')
            ->join('subject_tag', 'tag_talk.tag_id', '=', 'subject_tag.tag_id')
            ->where('tag_talk.talk_id', '=', $this->id)
            ->pluck('subject_tag.subject_id');
        return Subject::whereIn('id', $subjectIds);
    }

    // TODO rethink
    public function getPath($lng = 'en')
    {
        $path = $lng === 'th' ? '/new/th/talks/' : '/new/talks/';
        return $path . $this->id . '-' . str_slug($this->title);
    }

    // TODO yuck
    public function getSummaryHtml()
    {
        $func = new \App\Legacy\Func();
        return $func->abridge($this->body, 200);
    }
}
