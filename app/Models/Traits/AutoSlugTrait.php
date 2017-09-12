<?php

namespace App\Models\Traits;

trait AutoSlugTrait
{
    /**
     * Sets the attributes $name and 'slug'.
     *
     * @param string $name
     * @param mixed $value
     * @return void
     */
    protected function setAutoSlugTo($name, $value)
    {
        $this->attributes[$name] = $value;
        $this->attributes['slug'] = str_slug($value);
    }
}
