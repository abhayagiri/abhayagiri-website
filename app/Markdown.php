<?php

namespace App;

use Parsedown;
use League\HTMLToMarkdown\HtmlConverter;
use Michelf\SmartyPants;

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
        $html = (new MyParsedown)
            ->setBreaksEnabled(true)
            ->text($markdown);
        $parser = new SmartyPants;
        $parser->do_dashes = 2; // en and em-dashes
        $html = $parser->transform($html);
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
        $converter = new HtmlConverter(['strip_tags' => true]);
        $markdown = $converter->convert($html);
        $markdown = static::cleanChars($markdown);
        $markdown = preg_replace('/ ?\. \. \. ?/', '...', $markdown);
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

    protected static $cleanCharsMap = [
        '/(‘|’|‚|‛)/' => "'",
        '/—/' => '---',
        '/–/' => '--',
        '/…/' => '...',
        '/(“|”|„|‟)/' => '"',
    ];

    public static function cleanChars($text)
    {
        return preg_replace(
            array_keys(static::$cleanCharsMap),
            array_values(static::$cleanCharsMap),
            $text
        );
    }
}
