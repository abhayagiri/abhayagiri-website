<?php

namespace App\Models;

use App\Utilities\ImageCache;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Tale extends Model
{
    use CrudTrait;
    use SoftDeletes;
    use Traits\FilterThai;
    use Traits\HasAssociated;
    use Traits\HasPath;
    use Traits\HasPostedAtCommonOrder;
    use Traits\ImageCrudColumnTrait;
    use Traits\ImagePathTrait;
    use Traits\IsSearchable;
    use Traits\LocalDateTimeTrait;
    use Traits\LocalizedAttributes;
    use Traits\MarkdownHtmlTrait;
    use Traits\MediaPathTrait;
    use Traits\PostedAtTrait;
    use Traits\RevisionableTrait;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'deleted_at', 'created_at', 'updated_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'draft' => 'boolean',
        'posted_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['body_en_html', 'body_th_html', 'path', 'image_url'];

    /**
     * The attributes that should not be revisioned.
     *
     * @var array
     */
    protected $dontKeepRevisionOf = [
        'deleted_at',
    ];

    /**
     * Override to store the creation as a revision
     *
     * @var bool
     */
    protected $revisionCreationsEnabled = true;

    /**
     * The maximum number of records that should be indexed in testing
     * environments. A negative number means all records.
     *
     * @var int
     */
    protected $testingSearchMaxRecords = 10;

    /*
     * Accessors and Mutators
     */

    /**
     * Return slug from title.
     *
     * @return string
     */
    public function getSlugAttribute(): string
    {
        return str_slug($this->getAttribute('title'));
    }

    /*
     * Relationships
     */

    public function author()
    {
        return $this->belongsTo('App\Models\Author');
    }

    /**
     * Return a URL for the image suitable for an RSS feed.
     *
     * @return string
     */
    public function getRssImageUrl(): ?string
    {
        return ImageCache::getMediaUrl($this->image_url, 600);
    }
}
