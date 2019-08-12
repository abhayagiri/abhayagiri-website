<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Lang;
use Venturecraft\Revisionable\RevisionableTrait;

use App\Models\Danalist;
use App\Models\Resident;
use App\Legacy;

class Subpage extends Model
{
    use CrudTrait;
    use RevisionableTrait;
    use SoftDeletes;
    use Traits\LocalDateTimeTrait;
    use Traits\LocalizedAttributes;
    use Traits\MarkdownHtmlTrait;
    use Traits\PostedAtTrait;

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
    protected $appends = ['body_html_en', 'body_html_th', 'breadcrumbs', 'subnav'];

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
     * @var boolean
     */
    protected $revisionCreationsEnabled = true;

    /**********
     * Scopes *
     **********/

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

    /**************************
     * Accessors and Mutators *
     **************************/

    public function getPathAttribute()
    {
        return $this->getPath(Lang::locale());
    }

    public function getBreadcrumbsAttribute()
    {
        return collect([
            (object) [
                'title_en' => $this->title_en,
                'title_th' => $this->title_th,
                'path' => $this->path,
                'last' => true,
            ]
        ]);
    }

    public function getSubnavAttribute()
    {
        return $this->siblings()->get()->map(function($subpage) {
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

    /**********
     * Legacy *
     **********/

    public static function getLegacySubpage($page, $subpage, $subsubpage)
    {
        if ($page === 'community' && $subpage === 'residents' && $subsubpage) {
            return Resident::where('slug', $subsubpage)->first();
        } else if ($page && !$subpage) {
            return static::public()
                ->where('page', $page)
                ->orderBy('rank')->orderBy('title_en')
                ->first();
        } else {
            return static::public()
                ->where('page', $page)
                ->where('subpath', static::makeSubpath($subpage, $subsubpage))
                ->first();
        }
    }

    protected static function makeSubpath($subpage, $subsubpage)
    {
        return '' . $subpage . ($subsubpage ? ('/' . $subsubpage) : '');
    }

    /*********
     * Other *
     *********/

    public function getPath($lng = 'en')
    {
        return ($lng === 'th' ? '/th' : '') .
            '/' . $this->page . '/' . $this->subpath;
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

}
