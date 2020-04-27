<?php

namespace App\Models;

use App\Utilities\HtmlToText;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Lang;

class Book extends Model
{
    use CrudTrait;
    use SoftDeletes;
    use Traits\AutoSlugTrait;
    use Traits\HasPath;
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

    /**
     * Override to store the creation as a revision
     *
     * @var bool
     */
    protected $revisionCreationsEnabled = true;

    /*
     * Relationships *
     */

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
        $iconHtml = function ($title, $value, $icon, $link = true) {
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
     * Determine if the model should be searchable.
     *
     * @return bool
     */
    public function shouldBeSearchable(): bool
    {
        return $this->isPublic();
    }

    /**
     * Return the Aloglia indexable data array for the model.
     *
     * @see splitText()
     *
     * @return array
     */
    public function toSearchableArray(): array
    {
        $result = [
            'class' => get_class($this),
            'id' => $this->id,
            'text' => [
                'path_en' => $this->getPath('en'),
                'path_th' => $this->getPath('th'),
                'author_en' => $this->getAuthorTitles('en'),
                'author_th' => $this->getAuthorTitles('th'),
            ],
        ];
        if ($this->language->code === 'th') {
            $result['text']['title_en'] = '';
            $result['text']['title_th'] = $this->alt_title_th ?: $this->title;
            $result['text']['body_en'] = '';
            $result['text']['body_th'] = HtmlToText::toText($this->description_html_th);
        } else {
            $result['text']['title_en'] = $this->alt_title_en ?: $this->title;
            $result['text']['title_th'] = '';
            $result['text']['body_en'] = HtmlToText::toText($this->description_html_en);
            $result['text']['body_th'] = '';
        }
        return $result;
    }
}
