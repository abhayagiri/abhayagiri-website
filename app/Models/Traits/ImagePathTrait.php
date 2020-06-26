<?php

namespace App\Models\Traits;

use App\Models\Setting;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

/**
 * Note: this trait also requires MediaPathTrait.
 */
trait ImagePathTrait
{
    /**
     * Get the default image setting.
     *
     * @return void
     */
    public static function getDefaultImageSetting(): Setting
    {
        $type = Str::plural(Str::snake(class_basename(self::class)));
        return Setting::getByKey('default_images.' . $type);
    }

    /**
     * Attribute getter for image_path.
     *
     * @return string
     */
    public function getImageMediaPathAttribute(): string
    {
        if ($value = $this->getMediaPathFrom('image_path')) {
            return $value;
        }

        $setting = static::getDefaultImageSetting();
        if ($path = $setting->path) {
            return $path;
        }

        return '/media/default.jpg';
    }

    /**
     * Return the image preset path.
     *
     * @param  string  $preset
     * @param  string  $lng
     *
     * @return string
     */
    public function getImagePresetPath(
        string $preset = 'thumb',
        ?string $lng = null
    ): string {
        if ($lng === null) {
            $lng = Lang::locale();
        }
        $routePrefix = $lng === 'th' ? 'th.' : '';
        return route(
            $routePrefix . $this->getRouteName() . '.image',
            [$this->getRouteId(false), $preset, 'jpg']
        );
    }

    /**
     * Return the image_preset_path attribute.
     *
     * @return string
     */
    public function getImagePresetPathAttribute(): string
    {
        return $this->getImagePresetPath();
    }

    /**
     * Return the image preset url.
     *
     * @param  string  $preset
     * @param  string  $lng
     *
     * @return string
     */
    public function getImagePresetUrl(
        string $preset = 'thumb',
        ?string $lng = null
    ): string
    {
        return url($this->getImagePresetPath($preset, $lng));
    }

    /**
     * Return the image_preset_url attribute.
     *
     * @return string
     */
    public function getImagePresetUrlAttribute(): string
    {
        return $this->getImagePresetUrl();
    }

    /**
     * Attribute getter for image_url.
     *
     * @return string
     */
    public function getImageUrlAttribute(): string
    {
        return $this->getImageMediaPathAttribute();
    }

    /**
     * Return the image_path with defaults relative to media.
     *
     * @return string
     */
    public function getRelativeImagePathWithDefaults()
    {
        return preg_replace('_^/media/_', '', $this->getImageUrlAttribute());
    }

    /**
     * Safely set the image path.
     *
     * @param mixed $value
     */
    public function setImagePathAttribute($value)
    {
        $this->setMediaPathAttributeTo('image_path', $value);
    }
}
