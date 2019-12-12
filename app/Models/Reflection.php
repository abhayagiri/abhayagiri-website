<?php

namespace App\Models;

use App\Legacy;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Lang;

class Reflection extends Model
{
    use CrudTrait;
    use SoftDeletes;
    use Traits\AutoSlugTrait;
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
    protected $appends = ['body_html', 'image_url'];

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
     * @var boolean
     */
    protected $revisionCreationsEnabled = true;

    /**************************
     * Accessors and Mutators *
     **************************/

    /**
     * Return HTML for body.
     *
     * @return string|null
     */
    public function getBodyHtmlAttribute() : ?string
    {
        return $this->getMarkdownHtmlFrom('body', Lang::getLocale());
    }

    /*****************
     * Relationships *
     *****************/

    public function author()
    {
        return $this->belongsTo('App\Models\Author');
    }

    public function language()
    {
        return $this->belongsTo('App\Models\Language');
    }

    /**********
     * Legacy *
     **********/

    public static function getLegacyDatatables($get)
    {
        $totalQuery = static::public();
        $displayQuery = clone $totalQuery;
        Legacy::scopeDatatablesSearch($get, $displayQuery, [
            'title', 'alt_title_en', 'alt_title_th', 'body',
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
            'title' => Legacy::getTitleWithAlt($this, $language),
            'author' => Legacy::getAuthor($this->author, $language),
            'author_image_url' => $this->author->image_url,
            'body' => $this->body_html,
            'date' => $this->local_posted_at,
        ];
    }

    public static function getLegacyHomeReflection($language = 'English')
    {
        return static::public()
            ->postOrdered()
            ->first()
            ->toLegacyArray($language);
    }

    /*********
     * Other *
     *********/

    public function getPath($lng = 'en')
    {
        return ($lng === 'th' ? '/th' : '') .
            '/reflections/' . $this->id . '-' . $this->slug;
    }
}
