<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class News extends Model
{
    use CrudTrait;
    use SoftDeletes;
    use Traits\AutoSlugTrait;
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
    protected $guarded = ['id', 'slug', 'deleted_at', 'created_at', 'updated_at'];

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
        'check_translation' => 'boolean',
        'draft' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['body_html_en', 'body_html_th', 'path', 'image_url'];

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
     * The maximum number of records that should be indexed in testing
     * environments. A negative number means all records.
     *
     * @var int
     */
    protected $testingSearchMaxRecords = 10;

    /*
     * Scopes *
     */

    /**
     * Return a scope orderded by rank.
     *
     * @param Illuminate\Database\Eloquent\Builder $query
     *
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeRankOrdered($query)
    {
        $coalesceSql = DB::raw('COALESCE(' . $this->getTable() . '.rank, 100000) asc');
        return $query->orderByRaw($coalesceSql);
    }

    /**
     * Return a scope of news posts to show on the home page.
     *
     * @param Illuminate\Database\Eloquent\Builder $query
     *
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeHome($query)
    {
        return $this->scopePostOrdered($this->scopeRankordered($this->scopePublic($query)))
                    ->limit(setting('home.news_count')->value);
    }
}
