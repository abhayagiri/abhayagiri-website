<?php

namespace App\Models\Traits;

use App\Models\Setting;

/**
 * Note: thist trait also requires MediaPathTrait.
 */
trait ImagePathTrait
{
    /**
     * Attribute getter for image_url.
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        if ($value = $this->getMediaPathFrom('image_path')) {
            return $value;
        };

        if ($defaultImage = Setting::where('key', sprintf('%s.default_image_file', strtolower(str_plural(class_basename(self::class)))))->get()->first()) {
            if ($url = $defaultImage->value_media_url) {
                return $url;
            }
        }

        return '/media/images/speakers/speakers_abhayagiri_sangha.jpg';
    }

    /**
     * Safely set the image path.
     */
    public function setImagePathAttribute($value)
    {
        $this->setMediaPathAttributeTo('image_path', $value);
    }
}
