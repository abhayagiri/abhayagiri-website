<?php

namespace App\Models\Traits;

use App\Markdown;

trait MarkdownHtmlTrait
{
    /**
     * Return HTML for body.
     *
     * @return string
     */
    protected function getBodyHtmlAttribute()
    {
        return $this->getMarkdownHtmlFrom('body', 'en');
    }

    /**
     * Return HTML for body_en.
     *
     * @return string
     */
    protected function getBodyHtmlEnAttribute()
    {
        return $this->getMarkdownHtmlFrom('body_en', 'en');
    }

    /**
     * Return HTML for body_th.
     *
     * @return string
     */
    protected function getBodyHtmlThAttribute()
    {
        return $this->getMarkdownHtmlFrom('body_th', 'th');
    }

    /**
     * Return HTML for description_en.
     *
     * @return string
     */
    protected function getDescriptionHtmlEnAttribute()
    {
        return $this->getMarkdownHtmlFrom('description_en', 'en');
    }

    /**
     * Return HTML for description_th.
     *
     * @return string
     */
    protected function getDescriptionHtmlThAttribute()
    {
        return $this->getMarkdownHtmlFrom('description_th', 'th');
    }

    /**
     * Return a markdown attribute as HTML.
     *
     * @param string name
     * @param string lng
     * @return string
     */
    protected function getMarkdownHtmlFrom($name, $lng)
    {
        $value = $this->getAttribute($name);
        if ($value) {
            $html = Markdown::toHtml($value, $lng);
            // Convert tables to striped tables
            $html = preg_replace('/<table>/', '<table class="table table-striped">', $html);
            return $html;
        } else {
            return null;
        }
    }
}
