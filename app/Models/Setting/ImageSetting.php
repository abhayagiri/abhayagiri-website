<?php

namespace App\Models\Setting;

use App\Models\Setting;
use App\Models\Traits\MediaPathTrait;
use App\Utilities\ImageCache;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Parental\HasParent;

class ImageSetting extends Setting
{
    use HasParent;
    use MediaPathTrait;

    /**
     * The height of a crud image;
     *
     * @var int
     */
    const CRUD_HEIGHT = 50;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'value' => 'string',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['key', 'media_path', 'value'];

    /**
     * Callback for adding admin CRUD fields.
     *
     * @param  \Backpack\CRUD\app\Http\Controllers\CrudController  $controller
     *
     * @return void
     */
    public function addCrudFields(CrudController $controller): void
    {
        if ($this->value) {
            $controller->crud->addField([
                'type' => 'custom_html',
                'name' => 'current_image',
                'value' => '<label>Current Image</label><div>' .
                           $this->getCrudImageHtml() . '</div>',
            ]);
        }
        $controller->crud->addField([
            'type' => 'browse',
            'name' => 'media_path',
            'label' => 'Image',
        ]);
    }

    /**
     * Return HTML for the value column in the admin CRUD list.
     *
     * @return string
     */
    public function getCrudListHtml(): string
    {
        return $this->getCrudImageHtml();
    }

    /**
     * Get the admin CRUD validation rules.
     *
     * @return array
     */
    public function getCrudRules(): array
    {
        return [
            'media_path' => 'required',
        ];
    }

    /**
     * Getter for media_path.
     *
     * @return string|null
     */
    public function getMediaPathAttribute(): ?string
    {
        return $this->value;
    }

    /**
     * Getter for path.
     *
     * @return string|null
     */
    public function getPathAttribute(): ?string
    {
        return $this->getMediaPathFrom('value');
    }

    /**
     * Getter for URL.
     *
     * @return string|null
     */
    public function getUrlAttribute(): ?string
    {
        return $this->getMediaUrlFrom('value');
    }

    /**
     * Setter for media_path.
     *
     * @return string|null
     */
    public function setMediaPathAttribute(?string $value)
    {
        $this->value = $this->resolveMediaPath($value);
    }

    protected function getCrudImageHtml(): string
    {
        if ($this->value) {
            $imageUrl = ImageCache::getMediaUrl(
                $this->media_path,
                null,
                static::CRUD_HEIGHT
            );
            return '<a href="' . e($this->url) .
                   '" target="_blank"><img src="' . e($imageUrl) . '"></a>';
        } else {
            return '';
        }
    }
}
