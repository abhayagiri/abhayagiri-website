<?php

namespace App\Models;

use App\Utilities\HtmlToText;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Lang;

class Book extends Model
{
    use CrudTrait;
    use SoftDeletes;
    use Traits\AutoSlugTrait;
    use Traits\HasAltTitle;
    use Traits\HasAssociated;
    use Traits\HasPath;
    use Traits\HasPostedAtCommonOrder;
    use Traits\ImageCrudColumnTrait;
    use Traits\ImagePathTrait;
    use Traits\IsSearchable;
    use Traits\LocalDateTimeTrait;
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
        'request' => 'boolean',
        'draft' => 'boolean',
        'published_on' => 'datetime',
        'posted_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['description_html_en', 'description_html_th',
        'image_url', 'pdf_url', 'epub_url', 'mobi_url', 'url_title'];

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
    protected $slugFrom = 'getSlugFromTitleAndAltTitleEn';

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
     * Relationships *
     */

    public function language()
    {
        return $this->belongsTo('App\Models\Language');
    }

    public function author()
    {
        return $this->belongsTo('App\Models\Author')->withTrashed();
    }

    public function author2()
    {
        return $this->belongsTo('App\Models\Author');
    }

    /*
     * Accessors and Mutators *
     */

    public function getPdfUrlAttribute()
    {
        return $this->getMediaPathFrom('pdf_path');
    }

    public function setPdfPathAttribute($value)
    {
        $this->setMediaPathAttributeTo('pdf_path', $value);
    }

    public function getEpubUrlAttribute()
    {
        return $this->getMediaPathFrom('epub_path');
    }

    public function setEpubPathAttribute($value)
    {
        $this->setMediaPathAttributeTo('epub_path', $value);
    }

    public function getMobiUrlAttribute()
    {
        return $this->getMediaPathFrom('mobi_path');
    }

    public function setMobiPathAttribute($value)
    {
        $this->setMediaPathAttributeTo('mobi_path', $value);
    }

    public function getUrlTitleAttribute()
    {
        return '' . $this->getAttribute('id') . '-' . $this->getAttribute('slug');
    }

    /**
     * The accessor for getAuthorTitles().
     *
     * @return string|null
     */
    public function getAuthorTitlesAttribute(): ?string
    {
        return $this->getAuthorTitles(Lang::locale());
    }

    /**
     * Return the local-aware titles of the author(s) concatenated with ', '.
     *
     * @param  string|null  $lng
     *
     * @return string|null
     */
    public function getAuthorTitles(?string $lng): ?string
    {
        $titles = [];
        if ($this->author) {
            $title = $this->author->getTitle($lng);
            if ($title !== null) {
                $titles[] = $title;
            }
        }
        if ($this->author2) {
            $title = $this->author2->getTitle($lng);
            if ($title !== null) {
                $titles[] = $title;
            }
        }
        if ($titles) {
            return implode(', ', $titles);
        } else {
            return null;
        }
    }

    /**
     * Get crud column HTML for the book availability.
     *
     * @return string
     */
    public function getAvailabilityCrudColumnHtml()
    {
        return view('books.availability-icons', ['book' => $this]);
    }

    /**
     * Return the Aloglia indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray(): array
    {
        $result = $this->getBaseSearchableArray('description');
        $result['text']['author_en'] = $this->getAuthorTitles('en');
        $result['text']['author_th'] = $this->getAuthorTitles('th');
        $result['text']['title_en'] = $this->title_with_alt_en;
        $result['text']['title_th'] = $this->title_with_alt_th;
        if ($result['text']['title_en'] === $result['text']['title_th']) {
            if ($this->language->code === 'th') {
                $result['text']['title_en'] = '';
            } else {
                $result['text']['title_th'] = '';
            }
        }
        return $result;
    }

    /**
     * @param Builder $query
     * @param $filters
     * @return \Illuminate\Database\Concerns\BuildsQueries|Builder|mixed
     */
    public function scopeFiltered(Builder $query, $filters)
    {
        return $query->when(
            $filters['author_id'] && $filters['author_id'] !== 'all',
            function ($query) use ($filters) {
                return $query->where('author_id', $filters['author_id']);
            }
        )->when(
            $filters['language_id'] && $filters['language_id'] !== 'all',
            function ($query) use ($filters) {
                return $query->where('language_id', $filters['language_id']);
            }
        )->when(
            $filters['request'] !== null && $filters['request'] !== 'all',
            function ($query) use ($filters) {
                return $query->where('request', (int) $filters['request']);
            }
        );
    }
}
