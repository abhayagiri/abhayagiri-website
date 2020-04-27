<?php

namespace App\Models\Traits;

use App\Models\Setting;

/**
 * Note: thist trait also requires MediaPathTrait.
 */
trait ImagePathTrait
{
    /**
     * Get the default image file from settings.
     *
     * @return void
     */
    public static function getDefaultImageSetting()
    {
        return Setting::where('key', sprintf('%s.default_image_file', strtolower(str_plural(class_basename(self::class)))))->get()->first();
    }

    /**
     * Attribute getter for image_url.
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        if ($value = $this->getMediaPathFrom('image_path')) {
            return $value;
        }

        if ($defaultImage = self::getDefaultImageSetting()) {
            if ($url = $defaultImage->value_media_url) {
                return $url;
            }
        }

        return '/media/images/speakers/speakers_abhayagiri_sangha.jpg';
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
