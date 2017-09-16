<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

use App\Legacy;
use App\Util;

class Book extends Model
{
    use CrudTrait;
    use RevisionableTrait;
    use SoftDeletes;
    use Traits\AutoSlugTrait;
    use Traits\LocalDateTimeTrait;
    use Traits\ImageCrudColumnTrait;
    use Traits\ImagePathTrait;
    use Traits\MarkdownHtmlTrait;
    use Traits\MediaPathTrait;
    use Traits\PostedAtTrait;

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
    protected $dates = ['published_on', 'posted_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'check_translation' => 'boolean',
        'request' => 'boolean',
        'draft' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['description_html_en', 'description_html_th',
        'image_url', 'pdf_url', 'epub_url', 'mobi_url',
        'local_posted_at', 'url_title'];

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

    /*****************
     * Relationships *
     *****************/

    public function language()
    {
        return $this->belongsTo('App\Models\Language');
    }

    public function author()
    {
        return $this->belongsTo('App\Models\Author');
    }

    public function author2()
    {
        return $this->belongsTo('App\Models\Author');
    }

    /**************************
     * Accessors and Mutators *
     **************************/

    public function getPdfUrlAttribute()
    {
        return $this->getMediaUrlFrom('pdf_path');
    }

    public function setPdfPathAttribute($value)
    {
        $this->setMediaPathAttributeTo('pdf_path', $value);
    }

    public function getEpubUrlAttribute()
    {
        return $this->getMediaUrlFrom('epub_path');
    }

    public function setEpubPathAttribute($value)
    {
        $this->setMediaPathAttributeTo('epub_path', $value);
    }

    public function getMobiUrlAttribute()
    {
        return $this->getMediaUrlFrom('mobi_path');
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
     * Get crud column HTML for the book availability.
     *
     * @return string
     */
    public function getAvailabilityCrudColumnHtml()
    {
        $iconHtml = function($title, $value, $icon, $link = true) {
            if ($value) {
                $html = '<i title="' . $title . '" class="fa fa-' . $icon . '"></i>';
                if ($link) {
                    $html = '<a href="' . e($value) .
                        '" target="_blank">' . $html . '</a>';
                }
            } else {
                $html = '<i class="fa fa-square-o"></i>';
            }
            return $html;
        };
        return
            $iconHtml('Available', $this->request, 'book', false) . ' ' .
            $iconHtml('PDF', $this->pdf_url, 'file-pdf-o') . ' ' .
            $iconHtml('ePUB', $this->epub_url, 'file-text-o') . ' ' .
            $iconHtml('Mobi', $this->mobi_url, 'amazon');
    }

    /**********
     * Legacy *
     **********/

    public static function getLegacyDatatables($get)
    {
        $totalQuery = static::public();

        $displayQuery = clone $totalQuery;
        Legacy::scopeDatatablesSearch($get, $displayQuery, [
            'title', 'subtitle', 'alt_title_en', 'alt_title_th',
            'description_en', 'description_th',
            'pdf_path', 'epub_path', 'mobi_path'
        ]);

        $category = array_get($get, 'sSearch_0', 'All');
        switch ($category) {
            case 'pdf':
                $books = $displayQuery->whereNotNull('pdf_path');
                break;
            case 'ePub':
                $books = $displayQuery->whereNotNull('epub_path');
                break;
            case 'mobi':
                $books = $displayQuery->whereNotNull('mobi_path');
                break;
            case 'Print Copy':
                $books = $displayQuery->where('request', true);
                break;
        }

        $dataQuery = clone $displayQuery;
        $dataQuery
            ->orderBy('posted_at', 'desc')
            ->with('author');

        return Legacy::getDatatables($get, $totalQuery, $displayQuery, $dataQuery);
    }

    public function toLegacyArray($language = 'English')
    {
        return [
            'id' => $this->id,
            'url_title' => $this->id . '-' . $this->slug,
            'title' => Legacy::getTitleWithAlt($this, $language),
            'author' => Legacy::getAuthor($this->author, $language),
            'body' => Legacy::getEnglishOrThai(
                $this->description_html_en,
                $this->description_html_th, $language),
            'date' => $this->local_posted_at,
            'cover' => $this->image_url,
            'pdf' => $this->pdf_url,
            'epub' => $this->epub_url,
            'mobi' => $this->mobi_url,
            'weight' => $this->weight,
            'request' => $this->request,
        ];
    }

    /*********
     * Other *
     *********/

    public function getPath($lng = 'en')
    {
        return ($lng === 'th' ? '/th' : '') .
            '/books/' . $this->id . '-' . $this->slug;
    }
}
