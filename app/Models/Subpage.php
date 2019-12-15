<?php

namespace App\Models;

use App\Markdown;
use App\Utilities\HtmlToText;
use App\Utilities\TextSplitter;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Lang;

class Subpage extends Model
{
    use CrudTrait;
    use SoftDeletes;
    use Traits\IsSearchable;
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

    /**********
     * Legacy *
     **********/

    public static function getLegacySubpage($page, $subpage, $subsubpage)
    {
        if ($page === 'community' && $subpage === 'residents' && $subsubpage) {
            return Resident::where('slug', $subsubpage)->first();
        } elseif ($page && ! $subpage) {
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

    /*********
     * Other *
     *********/

    /**
     * Get the Subpage's path based on language.
     *
     * @param string $lng
     *
     * @return string
     */
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

    /**
     * Return the Aloglia indexable data array for the model.
     *
     * @see splitText()
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'class' => get_class($this),
            'id' => $this->id,
            'text' => [
                'path_en' => $this->getPath('en'),
                'path_th' => $this->getPath('th'),
                'title_en' => $this->title_en,
                'title_th' => $this->title_th,
                'body_en' => HtmlToText::toText($this->body_html_en),
                'body_th' => HtmlToText::toText($this->body_html_th),
            ],
        ];
    }

    /**
     * Split the text fields based on language and body size so that each
     * record is under 10kb.
     *
     * @see toSearchableArray()
     *
     * @param  array  $text
     * @return array
     */
    public function splitText($text)
    {
        $en = [
            'lng' => 'en',
            'path' => $text['path_en'],
            'title' => $text['title_en'],
            'body' => $text['body_en'],
        ];
        $records = $this->toSplitRecords($en, 'body', 'body_index');
        if ($text['title_th'] || $text['body_th']) {
            $th = [
                'lng' => 'th',
                'path' => $text['path_th'],
                'title' => $text['title_th'],
                'body' => $text['body_th'],
            ];
            $records = array_merge($records, $this->toSplitRecords($th, 'body', 'body_index'));
        }
        return $records;
    }

    private function toSplitRecords($record, $attribute, $indexName)
    {
        $records = [];
        $splitter = new TextSplitter(2000, 500, true);
        $text = $record[$attribute];
        foreach ($splitter->splitByParagraphs($text) as $i => $segment) {
            $newRecord = $record;
            $newRecord[$attribute] = $segment;
            $newRecord[$indexName] = $i;
            $records[] = $newRecord;
        }
        return $records;
    }

    /**
     * Determine if the model should be searchable.
     *
     * @return bool
     */
    public function shouldBeSearchable()
    {
        return $this->isPublic();
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
}
