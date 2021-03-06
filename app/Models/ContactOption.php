<?php

namespace App\Models;

use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class ContactOption extends Model
{
    use CrudTrait;
    use Traits\HasPath;
    use Traits\IsSearchable;
    use Traits\LocalDateTimeTrait;
    use Traits\MarkdownHtmlTrait;
    use Traits\RevisionableTrait;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
        'published' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['body_html_en', 'body_html_th', 'confirmation_html_en', 'confirmation_html_th'];

    /**
     * The attribute or method that derives the slug.
     *
     * @var string
     */
    protected $slugFrom = 'name_en';

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Return the contact preamble.
     *
     * @param  string|null  $lng
     *
     * @return string
     */
    public static function getPreamble($lang = null): string
    {
        return tp(setting('contact.preamble'), 'text', $lang);
    }

    /**
     * Return whether or not this is publicly visible.
     *
     * @return bool
     */
    public function isPublic(): bool
    {
        return $this->published;
    }

    /**
     * Return the Aloglia indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray(): array
    {
        $result = $this->getBaseSearchableArray('body');
        $result['text']['title_en'] = $this->name_en;
        $result['text']['title_th'] = $this->name_th;
        return $result;
    }

    /**
     * Return the base name for this model's route.
     *
     * @return string
     */
    protected function getRouteBaseName(): string
    {
        return 'contact';
    }
}
