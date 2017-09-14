<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

use App\Legacy;

class News extends Model
{
    use CrudTrait;
    //use RevisionableTrait;
    use SoftDeletes;
    use Traits\AutoSlugTrait;
    use Traits\DraftTrait;
    use Traits\DescriptionHtmlTrait;
    use Traits\LocalDateTimeTrait;
    use Traits\LocalPostedAtTrait;
    use Traits\ImageCrudColumnTrait;
    use Traits\ImagePathTrait;
    use Traits\MediaPathTrait;

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
    protected $appends = ['body_html_en', 'body_html_th',
        'image_url'];

    /**
     * The attributes that should not be revisioned.
     *
     * @var array
     */
    protected $dontKeepRevisionOf = [
        'slug', 'check_translation', 'deleted_at',
    ];

    /**
     * The friendly name for revisions.
     *
     * @return string
     */
    public function identifiableName()
    {
        return $this->title_en;
    }

    /*
     * Scopes.
     */

    public function scopeLatest($query)
    {
        return $query
            ->orderBy('posted_at', 'desc');
    }

    /*
     * Attribute accessors and mutators.
     */

    public function setTitleEnAttribute($value)
    {
        $this->setAutoSlugTo('title_en', $value);
    }

    /*
     * Legacy methods.
     */

    public static function getLegacyDatatables($get)
    {
        $totalQuery = self::public();

        $displayQuery = clone $totalQuery;
        Legacy::scopeDatatablesSearch($get, $displayQuery, [
            'title_en', 'title_th', 'body_en', 'body_th',
        ]);

        $dataQuery = clone $displayQuery;
        $dataQuery->latest();

        return Legacy::getDatatables($get, $totalQuery, $displayQuery, $dataQuery);
    }

    public function toLegacyArray($language = 'English')
    {
        $isThai = $language === 'Thai';
        $titleEn = $this->title_en;
        $titleTh = $this->title_th;
        $title = $isThai ? ($titleTh ? $titleTh : $titleEn) : $titleEn;
        $bodyHtmlEn = $this->body_html_en;
        $bodyHtmlTh = $this->body_html_th;
        $body = $isThai ? ($bodyHtmlTh ? $bodyHtmlTh : $bodyHtmlEn) : $bodyHtmlEn;
        return [
            'id' => $this->id,
            'url_title' => $this->id . '-' . $this->slug,
            'title' => $title,
            'body' => $body,
            'body_summary' => \App\Text::abridge($body, 750),
            'date' => $this->local_posted_at,
        ];
    }

    public static function getLegacyHomeNews()
    {
        return self::public()
            ->latest()
            ->limit(config('settings.home_news_count'))
            ->get();
    }

    /*
     * Other methods/properties.
     */

    public function getPath($lng = 'en')
    {
        return ($lng === 'th' ? '/th' : '') .
            '/news/' . $this->id . '-' . $this->slug;
    }
}
