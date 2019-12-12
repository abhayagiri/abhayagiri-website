<?php

namespace App\Models;

use Illuminate\Support\Str;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use CrudTrait;
    use Traits\MarkdownHtmlTrait;
    use Traits\MediaPathTrait;
    use Traits\MarkdownHtmlTrait;

    protected $table = 'settings';

    protected $fillable = ['value', 'value_media_path'];
    protected $appends = ['value_html'];

    protected static $markdown_keys = [
        'books.request_form_en',
        'books.request_form_th',
    ];

    public function getValueMediaPathAttribute()
    {
        return $this->getMediaPathFrom('value');
    }

    public function getValueMediaUrlAttribute()
    {
        return $this->getMediaUrlFrom('value');
    }

    public function getValueHtmlAttribute()
    {
        $language = Str::contains($this->key, '_en') ? 'en' : 'th';

        if ($language) {
            return $this->getMarkdownHtmlFrom('value', $language);
        }

        return $this->value;
    }

    public function setValueMediaPathAttribute($value)
    {
        $this->setMediaPathAttributeTo('value', $value);
    }

    /**
     * Apply settings to config.
     *
     * Also useful when in a tinker session as settings aren't loaded there by
     * default.
     *
     * @return void
     */
    public static function apply()
    {
        foreach (self::all() as $key => $setting) {
            Config::set('settings.' . $setting->key, $setting->value);
            if (in_array($setting->key, self::$markdown_keys)) {
                $htmlKey = 'settings.' . $setting->key . '_html';
                $lng = ends_with($htmlKey, '_th') ? 'th' : 'en';
                $html = $setting->getMarkdownHtmlFrom('value', $lng);
                Config::set($htmlKey, $html);
            }
        }
    }

    /**
     * Return the setting identified by $key or throw an Exception.
     *
     * @throws \Illuminate/Database/QueryException
     * @return Setting
     */
    public static function findByKey(string $key): Setting
    {
        return static::where(['key' => $key])->firstOrFail();
    }
}
