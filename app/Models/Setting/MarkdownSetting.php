<?php

namespace App\Models\Setting;

use App\Markdown;
use App\Models\Setting;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Parental\HasParent;

class MarkdownSetting extends Setting
{
    use HasParent;
    use LocalizedText {
        setTextEnAttribute as parentSetTextEnAttribute;
        setTextThAttribute as parentSetTextThAttribute;
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'value' => 'array',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['key', 'text_en', 'text_th', 'value'];

    /**
     * Callback for adding admin CRUD fields.
     *
     * @param  \Backpack\CRUD\app\Http\Controllers\CrudController  $controller
     *
     * @return void
     */
    public function addCrudFields(CrudController $controller): void
    {
        $controller->addMarkdownCrudField('text_en', 'English');
        $controller->addMarkdownCrudField('text_th', 'Thai');
    }

    /**
     * Return HTML for the value column in the admin CRUD list.
     *
     * @return string
     */
    public function getCrudListHtml(): string
    {
        return $this->limitCrudListHtml($this->text_en);
    }

    /**
     * Get the admin CRUD validation rules.
     *
     * @return array
     */
    public function getCrudRules(): array
    {
        return [
            'text_en' => 'required',
            'text_th' => 'nullable',
        ];
    }

    /**
     * Return html from text_en or text_th.
     *
     * @return null|string
     */
    public function getHtmlAttribute(): ?string
    {
        return tp($this, 'html');
    }

    /**
     * Return html from text_en.
     *
     * @return null|string
     */
    public function getHtmlEnAttribute(): ?string
    {
        return $this->toHtml($this->text_en, 'en');
    }

    /**
    * Return html from text_th.
     *
     * @return null|string
     */
    public function getHtmlThAttribute(): ?string
    {
        return $this->toHtml($this->text_th, 'th');
    }

    /**
     * Setter for text_en.
     *
     * @param  null|string  $value
     *
     * @return null|string
     */
    public function setTextEnAttribute(?string $value): ?string
    {
        if ($value !== null) {
            $value = Markdown::clean($value);
        }
        return $this->parentSetTextEnAttribute($value);
    }

    /**
     * Setter for text_th.
     *
     * @param  null|string  $value
     *
     * @return null|string
     */
    public function setTextThAttribute(?string $value): ?string
    {
        if ($value !== null) {
            $value = Markdown::clean($value);
        }
        return $this->parentSetTextThAttribute($value);
    }

    /**
     * Return Markdown as HTML.
     *
     * @param  string  $value
     * @param  string  $lng
     *
     * @return null|string
     */
    protected function toHtml(string $markdown, string $lng): ?string
    {
        if ($markdown !== null) {
            return Markdown::toHtml($markdown, $lng);
        } else {
            return null;
        }
    }
}
