<?php

namespace App\Models\Traits;

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
        $value = $this->getMediaPathFrom('image_path');
        if (!$value) {
            // TODO default?
            $value = '/media/images/speakers/speakers_abhayagiri_sangha.jpg';
        }
        return $value;
    }

    /**
     * Safely set the image path.
     */
    public function setImagePathAttribute($value)
    {
        $this->setMediaPathAttributeTo('image_path', $value);
    }
}
