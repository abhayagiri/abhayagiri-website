<?php

namespace App\Models\Traits;

trait ImageCrudColumnTrait
{
    /**
     * Return an icon of image_path for the admin crud.
     *
     * @return string
     */
    public function getImageCrudColumnHtml($name = 'image_path')
    {
        if ($this->{$name}) {
            $path = '/media/' . $this->{$name};
            return '<img width="50" src="' . \e($path) . '">';
        } else {
            return '';
        }
    }
}
