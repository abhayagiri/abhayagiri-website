<?php

namespace App\Models\Traits;

use App\Markdown;

trait DescriptionHtmlTrait
{
    /**
     * Return HTML for body_en.
     *
     * @return string
     */
    protected function getBodyHtmlEnAttribute()
    {
        return $this->getMarkdownHtmlFrom('body_en');
    }

    /**
     * Return HTML for body_th.
     *
     * @return string
     */
    protected function getBodyHtmlThAttribute()
    {
        return $this->getMarkdownHtmlFrom('body_th');
    }

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
            return Markdown::toHtml($value);
        } else {
            return null;
        }
    }
}
