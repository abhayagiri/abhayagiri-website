<?php

namespace App\Models;

trait ImageUrlTrait
{
    /**
     * Adds the imageUrl property to an array.
     *
     * To use, add the following method to the model:
     *
     *     public function toArray()
     *     {
     *         return $this->addImageUrl(parent::toArray());
     *     }
     */
    public function addImageUrl($array)
    {
        if ($array['imagePath']) {
            $array['imageUrl'] = '/media/' . $array['imagePath'];
        } else {
            // TEMP set a default image path if none is defined.
            $array['imageUrl'] = '/media/images/speakers/speakers_abhayagiri_sangha.jpg';
        }
        return $array;
    }
}
