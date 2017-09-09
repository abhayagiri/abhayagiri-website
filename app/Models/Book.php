<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Backpack\CRUD\CrudTrait;
use App\Util;

class Book extends Model
{
    use CrudTrait;
    use DraftTrait;
    use IconTrait;
    use CommonModelTrait;
    use SoftDeletes;

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
    protected $dates = ['published_on', 'posted_at', 'deleted_at'];

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
     * Get the language.
     */
    public function language()
    {
        return $this->belongsTo('App\Models\Language');
    }

    /**
     * Get the author.
     */
    public function author()
    {
        return $this->belongsTo('App\Models\Author');
    }

    /**
     * Get the second author.
     */
    public function author2()
    {
        return $this->belongsTo('App\Models\Author');
    }

    /**
     * Automatically set slug from title and alt_title_en.
     */
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->setSlug($value, array_get($this->attributes, 'alt_title_en'));
    }

    /**
     * Automatically set slug from title and alt_title_en.
     */
    public function setAltTitleEnAttribute($value)
    {
        $this->attributes['alt_title_en'] = $value;
        $this->setSlug(array_get($this->attributes, 'title'), $value);
    }

    /**
     * Return HTML for description_en.
     */
    public function getDescriptionHtmlEnAttribute()
    {
        return $this->getHtmlFromMarkdownAttribute('description_en');
    }

    /**
     * Return HTML for description_th.
     */
    public function getDescriptionHtmlThAttribute()
    {
        return $this->getHtmlFromMarkdownAttribute('description_th');
    }

    /**
     * Return a full url for image_path.
     */
    public function getImageUrlAttribute()
    {
        return $this->getMediaUrlAttribute('image_path');
    }

    /**
     * Return a full url for pdf_path.
     */
    public function getPdfUrlAttribute()
    {
        return $this->getMediaUrlAttribute('pdf_path');
    }

    /**
     * Return a full url for epub_path.
     */
    public function getEpubUrlAttribute()
    {
        return $this->getMediaUrlAttribute('epub_path');
    }

    /**
     * Return a full url for mobi_path.
     */
    public function getMobiUrlAttribute()
    {
        return $this->getMediaUrlAttribute('mobi_path');
    }

    /**
     * Return posted_at in local time.
     */
    public function getLocalPostedAtAttribute()
    {
        return $this->getLocalDateTimeAttribute('posted_at');
    }

    /**
     * Set posted_at (in UTC) from local_posted_at.
     */
    public function setLocalPostedAtAttribute($value)
    {
        return $this->setLocalDateTimeAttribute('posted_at', $value);
    }

    /**
     * Return legacy url_title.
     */
    public function getUrlTitleAttribute()
    {
        return '' . array_get($this->attributes, 'id') .
            '-' . rawurlencode(array_get($this->attributes, 'slug'));
    }

    /**
     * Get crud column HTML for the book availability.
     */
    public function getAvailabilityHtml()
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

    /**
     * Return results for legacy datatables.
     *
     * @param array $get
     * @return array
     */
    public static function getFromDatatables($get)
    {
        $books = self::public();
        $result = [
            'sEcho' => (int) array_get($get, 'sEcho'),
            'iTotalRecords' => $books->count(),
            'aaData' => [],
        ];

        $searchText = array_get($_GET, 'sSearch', '');
        if ($searchText !== '') {
            $likeQuery = '%' . Util::escapeLikeQueryText($searchText) . '%';
            $books = $books->where(function ($query) use ($likeQuery) {
                $query
                    ->where('title', 'LIKE', $likeQuery)
                    ->orWhere('subtitle', 'LIKE', $likeQuery)
                    ->orWhere('alt_title_en', 'LIKE', $likeQuery)
                    ->orWhere('alt_title_th', 'LIKE', $likeQuery)
                    ->orWhere('description_en', 'LIKE', $likeQuery)
                    ->orWhere('description_th', 'LIKE', $likeQuery)
                    ->orWhere('pdf_path', 'LIKE', $likeQuery)
                    ->orWhere('epub_path', 'LIKE', $likeQuery)
                    ->orWhere('mobi_path', 'LIKE', $likeQuery);
            });
        }

        $category = array_get($_GET, 'sSearch_0', 'All');
        switch ($category) {
            case 'pdf':
                $books = $books->whereNotNull('pdf_path');
                break;
            case 'ePub':
                $books = $books->whereNotNull('epub_path');
                break;
            case 'mobi':
                $books = $books->whereNotNull('mobi_path');
                break;
            case 'Print Copy':
                $books = $books->where('request', true);
                break;
        }

        $result['iTotalDisplayRecords'] = $books->count();

        $books = $books->orderBy('posted_at', 'desc')
            ->limit((int) array_get($get, 'iDisplayLength'))
            ->offset((int) array_get($get, 'iDisplayStart'))
            ->with('author')
            ->get();

        $result['books'] = $books;

        return $result;
    }

    protected function setSlug($title, $altTitleEn)
    {
        $slugFromTitle = str_slug($title);
        $slugFromAltTitleEn = str_slug($altTitleEn);
        $this->attributes['slug'] =
            $slugFromAltTitleEn ? $slugFromAltTitleEn :
            ($slugFromTitle ? $slugFromTitle : 'unknown');
   }
}
