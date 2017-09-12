<?php

namespace App\Models\Traits;

use Parsedown;

trait DescriptionHtmlTrait
{
    /**
     * Return HTML for description_en.
     *
     * @return string
     */
    protected function getDescriptionHtmlEnAttribute()
    {
        return $this->getMarkdownHtmlFrom('description_en');
    }

    /**
     * Return HTML for description_th.
     *
     * @return string
     */
    protected function getDescriptionHtmlThAttribute()
    {
        return $this->getMarkdownHtmlFrom('description_th');
    }

    /**
     * Return a markdown attribute as HTML.
     *
     * @param string name
     * @return string
     */
    protected function getMarkdownHtmlFrom($name)
    {
        $value = array_get($this->attributes, $name);
        if ($value) {
            return (new Parsedown())->text($value);
        } else {
            return null;
        }
    }
}
