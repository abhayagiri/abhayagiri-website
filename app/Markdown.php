<?php

namespace App;

use Parsedown;
use League\HTMLToMarkdown\HtmlConverter;
use Michelf\SmartyPants;
use SSD\SmartQuotes\Utf8CharacterSet;
use SSD\SmartQuotes\Factory as SmartQuotesFactory;

class MyParsedown extends Parsedown
{
    protected function inlineSpecialCharacter($excerpt)
    {
        // We handle quotes seperately.
    }
}

class Markdown
{
    /**
     * Convert Markdown to HTML.
     *
     * @param string $html
     * @return string
     */
    public static function toHtml($markdown)
    {
        $html = (new MyParsedown())->text($markdown);
        $html = SmartyPants::defaultTransform($html);
        return $html;
    }

    /**
     * Convert HTML to Markdown.
     *
     * @param string $html
     * @return string
     */
    public static function fromHtml($html, $allowedTags = '')
    {
        $converter = new HtmlConverter();
        $markdown = $converter->convert($html);
        $markdown = SmartQuotesFactory::filter(new Utf8CharacterSet, $markdown);
        $markdown = preg_replace('/…/', '...', $markdown);
        $markdown = strip_tags($markdown, $allowedTags);
        $markdown = preg_replace(
            '/\(https?:\/\/(www\.)?abhayagiri\.org/', '(', $markdown);
        $markdown = preg_replace('/\r/', '', $markdown);
        $markdown = preg_replace('/  +\n/', "\n\n", $markdown);
        $markdown = preg_replace('/\n\n+/', "\n\n", $markdown);
        if (substr_count($markdown, '_') > 12) {
            // Too many, assume a bad parse.
            $markdown = preg_replace('/_/', '', $markdown);
        }
        $markdown = trim($markdown);
        return $markdown ? $markdown : null;
    }
}
