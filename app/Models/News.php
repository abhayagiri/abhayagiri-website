<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use CrudTrait;
    use SoftDeletes;
    use Traits\AutoSlugTrait;
    use Traits\FilterThai;
    use Traits\HasAssociated;
    use Traits\HasRankOrder;
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'check_translation' => 'boolean',
        'draft' => 'boolean',
        'posted_at' => 'datetime',
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
     * Return the News in common ordering.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCommonOrder(Builder $query): Builder
    {
        return (
            $this->scopePostedAtOrder(
                    $this->scopeRankOrder($query)
            )->orderBy($this->getQualifiedKeyName(), 'desc')
        );
    }

    /**
     * Return a scope of news posts to show on the home page.
     *
     * @param Illuminate\Database\Eloquent\Builder $query
     *
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeHome(Builder $query): Builder
    {
        return $this->scopePostedAtOrder($this->scopeRankOrder($this->scopePublic($query)))
                    ->limit(setting('home.news_count')->value);
    }
}
