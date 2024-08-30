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
    use Traits\HasAltTitle;
    use Traits\HasAssociated;
    use Traits\HasPath;
    use Traits\HasPostedAtCommonOrder;
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

    /**
     * The maximum number of records that should be indexed in testing
     * environments. A negative number means all records.
     *
     * @var int
     */
    protected $testingSearchMaxRecords = 10;

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
     * Return the Aloglia indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray(): array
    {
        // Just assume English
        $result = $this->getBaseSearchableArray(null);
        $result['text']['title_en'] = $this->title;
        $result['text']['title_th'] = '';
        $result['text']['body_en'] = HtmlToText::toText($this->body_html);
        $result['text']['body_th'] = '';
        return $result;
    }
}
