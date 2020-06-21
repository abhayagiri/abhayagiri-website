<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subpage extends Model
{
    use CrudTrait;
    use SoftDeletes;
    use Traits\HasPath;
    use Traits\IsSearchable {
        shouldBeSearchable as parentShouldBeSearchable;
    }
    use Traits\LocalDateTimeTrait;
    use Traits\LocalizedAttributes;
    use Traits\MarkdownHtmlTrait;
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
        'check_translation' => 'boolean',
        'draft' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'body_html_en',
        'body_html_th',
        'breadcrumbs',
        'subnav',
        'path',
    ];

    /**
     * The attributes that should not be revisioned.
     *
     * @var array
     */
    protected $dontKeepRevisionOf = [
        'check_translation', 'deleted_at',
    ];

    /**
     * Override to store the creation as a revision
     *
     * @var bool
     */
    protected $revisionCreationsEnabled = true;

    /**
     * Override to store the creation as a revision
     *
     * @var string
     */
    protected $searchBodyField = 'body';

    /**
     * The maximum number of records that should be indexed in testing
     * environments. A negative number means all records.
     *
     * @var int
     */
    protected $testingSearchMaxRecords = 50;

    /*
     * Scopes *
     */

    /**
     * Return a scope to match the subpages matching path.
     *
     * @param Illuminate\Database\Eloquent\Builder $query
     * @param string $path
     *
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithPath(Builder $query, string $path) : Builder
    {
        $parts = explode('/', $path, 2);
        $query->where('page', $parts[0]);

        if (sizeof($parts) == 1) {
            $query->orderBy('rank');
        } else {
            $query->where('subpath', $parts[1]);
        }

        return $query;
    }

    /*
     * Accessors and Mutators *
     */

    public function getBreadcrumbsAttribute()
    {
        return collect([
            (object) [
                'title_en' => $this->title_en,
                'title_th' => $this->title_th,
                'path' => $this->path,
                'last' => true,
            ],
        ]);
    }

    public function getSubnavAttribute()
    {
        return $this->siblings()->get()->map(function ($subpage) {
            return (object) [
                'id' => $subpage->id,
                'title_en' => $subpage->title_en,
                'title_th' => $subpage->title_th,
                'path' => $subpage->path,
                'page' => $subpage->page,
                'subpath' => $subpage->subpath,
                'active' => $subpage->id === $this->id,
            ];
        });
    }

    /*
     * Other *
     */

    /**
     * Return the router ID.
     *
     * @return string|null
     */
    protected function getRouteId(): ?string
    {
        return $this->page . '/' . $this->subpath;
    }

    /**
     * Return the name for the show route.
     *
     * @return string
     */
    protected function getRouteName(): string
    {
        return 'subpages.path';
    }

    /**
     * Return all subpages with the same page attribute.
     *
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function siblings() : Builder
    {
        return static::public()
            ->where('page', $this->page)
            ->orderBy('rank')->orderBy('title_en');
    }

    /**
     * Generate the Subpage subpath.
     *
     * @param string $subpage
     * @param string $subsubpage
     *
     * @return string
     */
    protected static function makeSubpath(string $subpage, string $subsubpage)
    {
        return '' . $subpage . ($subsubpage ? ('/' . $subsubpage) : '');
    }

    /**
     * Determine if the model should be searchable.
     *
     * @return bool
     */
    public function shouldBeSearchable(): bool
    {
        // We don't want to surface the residents page because each resident
        // is shown on the index.
        return !($this->page === 'community' && $this->subpath === 'residents') &&
            $this->parentShouldBeSearchable();
    }
}
