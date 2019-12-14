<?php

namespace App\Models\Traits;

/**
 * Attributes that are commonly translated/localized.
 *
 * TODO: Move tp() helper to a Util class.
 */
trait LocalizedAttributes
{
    /**
     * Return the body attribute localized.
     *
     * @return string|null
     */
    public function getBodyAttribute() : ?string
    {
        return tp($this, 'body');
    }

    /**
     * Return the body_html attribute localized.
     *
     * @return string|null
     */
    public function getBodyHtmlAttribute() : ?string
    {
        return tp($this, 'bodyHtml');
    }

    /**
     * Return the description attribute localized.
     *
     * @return string|null
     */
    public function getDescriptionAttribute() : ?string
    {
        return tp($this, 'description');
    }

    /**
     * Return the description_html attribute localized.
     *
     * @return string|null
     */
    public function getDescriptionHtmlAttribute() : ?string
    {
        return tp($this, 'descriptionHtml');
    }

    /**
     * Return the title attribute localized.
     *
     * @return string|null
     */
    public function getTitleAttribute() : ?string
    {
        return tp($this, 'title');
    }
}
