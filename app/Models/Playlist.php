<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Venturecraft\Revisionable\RevisionableTrait;

use App\Models\Talk;
use App\Scopes\TitleEnScope;

class Playlist extends Model
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
     * @var boolean
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

    /**************************
     * Accessors and Mutators *
     **************************/

    public function getTalksPathAttribute()
    {
        return '/talks/collections/' . $this->getAttribute('group_id') . '/' .
            $this->getKey() . '-' . $this->getAttribute('slug');
    }

    public function getPathAttribute()
    {
        return $this->getTalksPathAttribute();
    }

    /*****************
     * Relationships *
     *****************/

    public function group()
    {
        return $this->belongsTo('App\Models\PlaylistGroup');
    }

    public function talks()
    {
        return $this->belongsToMany('App\Models\Talk');
    }

    /*********
     * Other *
     *********/

    public function getPath($lng = 'en')
    {
        return ($lng === 'th' ? '/th' : '') . $this->getAttribute('path');
    }
}
