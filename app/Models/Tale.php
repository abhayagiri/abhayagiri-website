<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Tale extends Model
{
    use CrudTrait;
    use SoftDeletes;
    use Traits\FilterThai;
    use Traits\HasPath;
    use Traits\IsSearchable;
    use Traits\LocalDateTimeTrait;
    use Traits\LocalizedAttributes;
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
    protected $guarded = ['id', 'deleted_at', 'created_at', 'updated_at'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['posted_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'draft' => 'boolean',
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
        $path = $this->image_url;
        if (Str::startsWith($path, '/media/')) {
            $path = substr($path, 7);
        }
        $encodedPath = implode('/', array_map('urlencode', explode('/', $path)));
        return url('image-cache/' . $encodedPath . '?w=600');
    }

    /**
     * Determine if the model should be searchable.
     *
     * @return bool
     */
    public function shouldBeSearchable(): bool
    {
        return $this->isPublic();
    }
}