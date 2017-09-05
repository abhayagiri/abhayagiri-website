<?php

namespace App\Models;

use Parsedown;

trait DescriptionTrait
{
    /**
     * Converts the description properties to HTML (from Markdown).
     *
     * To use, add the following method to the model:
     *
     *     public function toArray()
     *     {
     *         return $this->convertDescriptionsToHtml(parent::toArray());
     *     }
     */
    public function convertDescriptionsToHtml($array)
    {
        $array['description_html_en'] = $this->convertMarkdownToHtml($array['description_en']);
        $array['description_html_th'] = $this->convertMarkdownToHtml($array['description_th']);
        return $array;
    }

    /**
     * Convert markdown to HTML.
     *
     * @param string markdown
     * @return string
     */
    public function convertMarkdownToHtml($text)
    {
        $parsedown = new Parsedown();
        return $parsedown->text($text);
    }
}
