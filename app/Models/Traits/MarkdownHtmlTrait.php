<?php

namespace App\Models\Traits;

use App\Markdown;

trait MarkdownHtmlTrait
{
    /**
     * Clean and set a markdown field.
     *
     * @param string $name
     * @param string|null $markdown
     *
     * @return void
     */
    protected function cleanAndSetMarkdown(string $name, ?string $markdown)
    {
        if (!is_null($markdown)) {
            $markdown = Markdown::clean($markdown);
        }
        $this->attributes[$name] = $markdown;
    }

    /**
     * Return HTML for body_en.
     *
     * @return string|null
     */
    protected function getBodyHtmlEnAttribute() : ?string
    {
        return $this->getMarkdownHtmlFrom('body_en', 'en');
    }

    /**
     * Return HTML for body_th.
     *
     * @return string|null
     */
    protected function getBodyHtmlThAttribute() : ?string
    {
        return $this->getMarkdownHtmlFrom('body_th', 'th');
    }

    /**
     * Return HTML for confirmation_en.
     *
     * @return string
     */
    protected function getConfirmationHtmlEnAttribute() : ?string
    {
        return $this->getMarkdownHtmlFrom('confirmation_en', 'en');
    }

    /**
     * Return HTML for confirmation_th.
     *
     * @return string
     */
    protected function getConfirmationHtmlThAttribute() : ?string
    {
        return $this->getMarkdownHtmlFrom('confirmation_th', 'th');
    }

    /**
     * Return HTML for description_en.
     *
     * @return string|null
     */
    protected function getDescriptionHtmlEnAttribute() : ?string
    {
        return $this->getMarkdownHtmlFrom('description_en', 'en');
    }

    /**
     * Return HTML for description_th.
     *
     * @return string|null
     */
    protected function getDescriptionHtmlThAttribute() : ?string
    {
        return $this->getMarkdownHtmlFrom('description_th', 'th');
    }

    /**
     * Return a markdown attribute as HTML.
     *
     * @param string name
     * @param string lng
     *
     * @return string|null
     */
    protected function getMarkdownHtmlFrom(string $name, $lng)
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

    /**
     * Clean and set body.
     *
     * @param string|null $markdown
     *
     * @return void
     */
    protected function setBodyAttribute(?string $markdown)
    {
        $this->cleanAndSetMarkdown('body', $markdown);
    }

    /**
     * Clean and set body_en.
     *
     * @param string|null $markdown
     *
     * @return void
     */
    protected function setBodyEnAttribute(?string $markdown)
    {
        $this->cleanAndSetMarkdown('body_en', $markdown);
    }

    /**
     * Clean and set body_th.
     *
     * @param string|null $markdown
     *
     * @return void
     */
    protected function setBodyThAttribute(?string $markdown)
    {
        $this->cleanAndSetMarkdown('body_th', $markdown);
    }

    /**
     * Clean and set description_en.
     *
     * @param string|null $markdown
     *
     * @return void
     */
    protected function setDescriptionEnAttribute(?string $markdown)
    {
        $this->cleanAndSetMarkdown('description_en', $markdown);
    }

    /**
     * Clean and set description_th.
     *
     * @param string|null $markdown
     *
     * @return void
     */
    protected function setDescriptionThAttribute(?string $markdown)
    {
        $this->cleanAndSetMarkdown('description_th', $markdown);
    }
}
