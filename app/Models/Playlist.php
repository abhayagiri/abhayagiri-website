<?php

namespace App\Models;

use App\Scopes\TitleEnScope;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Playlist extends Model
{
    use CrudTrait;
    use SoftDeletes;
    use Traits\AutoSlugTrait;
    use Traits\LocalDateTimeTrait;
    use Traits\ImageCrudColumnTrait;
    use Traits\ImagePathTrait;
    use Traits\MarkdownHtmlTrait;
    use Traits\MediaPathTrait;
    use Traits\PostedAtTrait;
    use Traits\RevisionableTrait;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'slug', 'deleted_at', 'created_at', 'updated_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'check_translation' => 'boolean',
        'draft' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['description_html_en', 'description_html_th',
        'image_url', 'talks_path'];

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

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new TitleEnScope);
    }

    /**
     * The friendly name for revisions.
     *
     * @return string
     */
    public function identifiableName()
    {
        return $this->title_en;
    }

    /*
     * Accessors and Mutators *
     */

    public function getTalksPathAttribute()
    {
        return '/talks/collections/' . $this->getAttribute('group_id') . '/' .
            $this->getKey() . '-' . $this->getAttribute('slug');
    }

    public function getPathAttribute()
    {
        return $this->getTalksPathAttribute();
    }

    /**
     * Set YouTube playlist ID, automagically handling URLs.
     *
     * @param string $youtubePlaylistId
     *
     * @return void
     */
    public function setYoutubePlaylistIdAttribute(?string $youtubePlaylistId) : void
    {
        if (preg_match(
            '_^.+(?:playlist\?|watch\?v=(?:.+)&)list=([^&]+)(&.+)?$_',
            $youtubePlaylistId,
            $matches
        )) {
            $youtubePlaylistId = $matches[1];
        }
        $this->attributes['youtube_playlist_id'] = $youtubePlaylistId;
    }

    /*
     * Relationships *
     */

    public function group()
    {
        return $this->belongsTo('App\Models\PlaylistGroup');
    }

    public function talks()
    {
        return $this->belongsToMany('App\Models\Talk');
    }

    /*
     * Other *
     */

    /**
     * Filter (remove) YouTube Playlist IDs by those Playlists that have a
     * matching youtube_playlist_id.
     *
     * The result will be a collection of YouTube Playlist IDs without an
     * associated Playlist.
     *
     * @param iterable $playlistIds
     *
     * @return Illuminate\Support\Collection
     */
    public static function filterYouTubePlaylistIds(iterable $playlistIds)
                                                    : Collection
    {
        return (new Collection($playlistIds))->diff(
            static::withTrashed()->whereIn('youtube_playlist_id', $playlistIds)
                                 ->pluck('youtube_playlist_id')
        )->values();
    }

    public function getPath($lng = 'en')
    {
        return ($lng === 'th' ? '/th' : '') . $this->getAttribute('path');
    }
}
