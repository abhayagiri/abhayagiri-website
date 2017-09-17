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
    protected $appends = ['body_html_en', 'body_html_th', 'image_url'];

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
        $dataQuery->latest();
        return Legacy::getDatatables($get, $totalQuery, $displayQuery, $dataQuery);
    }

    public function toLegacyArray($language = 'English')
    {
        return [
            'id' => $this->id,
            'url_title' => $this->id . '-' . $this->slug,
            'title' => Legacy::getEnglishOrThai(
                $this->title_en, $this->title_th, $language),
            'body' => Legacy::getEnglishOrThai(
                $this->body_html_en, $this->body_html_th, $language),
            'date' => $this->local_posted_at,
        ];
    }

    public static function getLegacyHomeNews($language = 'English')
    {
        return static::public()
            ->latest()
            ->limit(config('settings.home_news_count'))
            ->get()->map(function($news) use ($language) {
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
}
