<?php

namespace App\Models;

trait IconTrait
{
    /**
     * Provides an icon to the admin crud.
     */
    public function getIconHtml()
    {
        if ($this->image_path) {
            $path = '/media/' . $this->image_path;
            return '<img width="50" src="' . \e($path) . '">';
        } else {
            return '';
        }
    }
}
