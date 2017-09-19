<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Lang;
use Venturecraft\Revisionable\RevisionableTrait;

use App\Legacy;

class Subpage extends Model
{
    use CrudTrait;
    use RevisionableTrait;
    use SoftDeletes;
    use Traits\LocalDateTimeTrait;
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
    protected $appends = ['body_html_en', 'body_html_th'];

    /**
     * The attributes that should not be revisioned.
     *
     * @var array
     */
    protected $dontKeepRevisionOf = [
        'check_translation', 'deleted_at',
    ];

    /**************************
     * Accessors and Mutators *
     **************************/

    /**
     * Return subpage'd HTML for body_en.
     *
     * @return string
     */
    protected function getBodyHtmlEnAttribute()
    {
        return $this->subpageMarkup($this->getMarkdownHtmlFrom('body_en'));
    }

    /**
     * Return subpage'd HTML for body_th.
     *
     * @return string
     */
    protected function getBodyHtmlThAttribute()
    {
        return $this->subpageMarkup($this->getMarkdownHtmlFrom('body_th'));
    }

    /**********
     * Legacy *
     **********/

    public static function getLegacyStatement($page, $subpage)
    {
        $query = static::public()->where('page', $page);
        if ($subpage) {
            $query->where('subpath', $subpage);
        } else {
            $query->orderBy('rank')->orderBy('subpath');
        }
        $model = $query->first();
        if ($model) {
            return [
                $model->toLegacyArray(),
                static::public()
                        ->where('page', $model->page)
                        ->get()->map(function($s) {
                    return $s->toLegacyArray();
                })->toArray(),
            ];
        } else {
            return [null, null];
        }
    }

    public static function getLegacyAjax($page, $subpage)
    {
        $model = static::public()
            ->where('page', $page)
            ->where('subpath', $subpage)
            ->first();
        return $model ? $model->toLegacyArray() : null;
    }

    public function toLegacyArray()
    {
        return [
            'id' => $this->id,
            'page' => $this->page,
            'url_title' => $this->subpath,
            'path' => $this->getPath(Lang::locale()),
            'title' => Legacy::getEnglishOrThai($this->title_en, $this->title_th),
            'body' => Legacy::getEnglishOrThai($this->body_html_en, $this->body_html_th),
            'date' => $this->local_posted_at,
        ];
    }

    /*********
     * Other *
     *********/

    public function getPath($lng = 'en')
    {
        return ($lng === 'th' ? '/th' : '') .
            '/' . $this->page . '/' . $this->subpath;
    }

    protected function subpageMarkup($html)
    {
        // Convert tables to striped tables
        $html = preg_replace('/<table>/', '<table class="table table-striped">', $html);
        return $html;
    }
}
