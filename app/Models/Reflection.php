<?php

namespace App\Models;

use App\Utilities\HtmlToText;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Lang;

class Reflection extends Model
{
    use CrudTrait;
    use SoftDeletes;
    use Traits\AutoSlugTrait;
    use Traits\HasPath;
    use Traits\IsSearchable;
    use Traits\LocalDateTimeTrait;
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
    protected $appends = ['body_html', 'path', 'image_url'];

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
     * Accessors and Mutators *
     */

    /**
     * Return HTML for body.
     *
     * @return string|null
     */
    public function getBodyHtmlAttribute() : ?string
    {
        return $this->getMarkdownHtmlFrom('body', Lang::getLocale());
    }

    /*
     * Relationships *
     */

    public function author()
    {
        return $this->belongsTo('App\Models\Author');
    }

    public function language()
    {
        return $this->belongsTo('App\Models\Language');
    }

    /*
     * Other *
     */

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
                'author_en' => $this->author->title_en,
                'author_th' => $this->author->title_th,
            ],
        ];
        $body = HtmlToText::toText($this->body_html);
        if ($this->language->code === 'th') { // Unlikely
            $result['text']['title_en'] = '';
            $result['text']['title_th'] = $this->alt_title_th ?: $this->title;
            $result['text']['body_en'] = '';
            $result['text']['body_th'] = $body;
        } else {
            $result['text']['title_en'] = $this->alt_title_en ?: $this->title;
            $result['text']['title_th'] = '';
            $result['text']['body_en'] = $body;
            $result['text']['body_th'] = '';
        }
        return $result;
    }
}
