<?php

namespace App\Models;

use App\Legacy;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

class News extends Model
{
    use CrudTrait;
    use SoftDeletes;
    use Traits\AutoSlugTrait;
    use Traits\IsSearchable;
    use Traits\LocalDateTimeTrait;
    use Traits\LocalizedAttributes;
    use Traits\ImageCrudColumnTrait;
    use Traits\ImagePathTrait;
    use Traits\MarkdownHtmlTrait;
    use Traits\MediaPathTrait;
    use Traits\PostedAtTrait {
        scopePostOrdered as scopePostOrderedWithoutRank;
    }
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
     * @var boolean
     */
    protected $revisionCreationsEnabled = true;

    /**********
     * Scopes *
     **********/

    /**
     * Return a scope orderded by rank and posted_at.
     *
     * @param Illuminate\Database\Eloquent\Builder $query
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopePostOrdered($query)
    {
        $coalesceSql = DB::raw('COALESCE(' . $this->getTable() . '.rank, 100000)');
        return $query
            ->orderByRaw($coalesceSql, 'asc')
            ->orderBy($this->getTable() . '.posted_at', 'desc');
    }

    /**************************
     * Accessors and Mutators *
     **************************/

    public function getPathAttribute()
    {
        return $this->getPath(Lang::locale());
    }

    /**********
     * Legacy *
     **********/

    public static function getLegacyDatatables($get)
    {
        $totalQuery = static::public();
        $displayQuery = clone $totalQuery;
        Legacy::scopeDatatablesSearch($get, $displayQuery, [
            'title_en', 'title_th', 'body_en', 'body_th',
        ]);
        $dataQuery = clone $displayQuery;
        $dataQuery->postOrdered();
        return Legacy::getDatatables($get, $totalQuery, $displayQuery, $dataQuery);
    }

    public function toLegacyArray($language = 'English')
    {
        return [
            'id' => $this->id,
            'url_title' => $this->id . '-' . $this->slug,
            'title' => Legacy::getEnglishOrThai(
                $this->title_en,
                $this->title_th,
                $language
            ),
            'body' => Legacy::getEnglishOrThai(
                $this->body_html_en,
                $this->body_html_th,
                $language
            ),
            'date' => $this->local_posted_at,
        ];
    }

    public static function getLegacyHomeNews($language = 'English')
    {
        return static::public()
            ->postOrdered()
            ->limit(config('settings.home.news.count'))
            ->get()->map(function ($news) use ($language) {
                return $news->toLegacyArray($language);
            });
    }

    /*********
     * Other *
     *********/

    public function getPath($lng = 'en')
    {
        return ($lng === 'th' ? '/th' : '') .
            '/news/' . $this->id . '-' . $this->slug;
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
