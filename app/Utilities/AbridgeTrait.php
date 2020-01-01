<?php

namespace App\Utilities;

trait AbridgeTrait
{
    /**
     * Truncatation HTML string.
     *
     * @type string
     */
    protected static $end = '&hellip;';

    /**
     * Remove all tags (convert to HTML text) and truncate it to $length. If
     * $keepLinks is true, then this will preserve <a> link tags.
     *
     * Note: this will overcount links of length 1.
     *
     * @param  string  $html
     * @param  int  $length
     * @param  bool  $keepLinks
     *
     * @return string
     */
    public static function abridge($html, $limit = 100, $keepLinks = true)
    {
        if ($limit < 1) {
            $limit = 1;
        }
        $options = [ 'do_links' => $keepLinks ? 'bbcode' : 'none' ];

        $text = HtmlToText::toText($html, $options);

        // Replace links with padded \e references.
        $links = [];
        if ($keepLinks) {
            // Ensure that text has no \e references (just in case).
            $text = preg_replace('/\e/u', '', $text);
            $text = preg_replace_callback(
                '_\[url=([^\]]*)\](.*?)\[/url\]_u',
                function ($matches) use (&$links) {
                    list($url, $display) = [ $matches[1], $matches[2] ];
                    if (preg_match('/^\s*$/u', $display)) {
                        return '';
                    }
                    $length = mb_strwidth($display, 'UTF-8');
                    $links[] = [ $url, $display, $length ];
                    return str_repeat("\e", max($length - 1, 1)) . ';';
                },
                $text
            );
        }

        // Replace multiple whitespaces with a single space and trim.
        $text = trim(preg_replace('/\s+/u', ' ', $text));

        // Truncate.
        if (mb_strwidth($text, 'UTF-8') > $limit) {
            $text = mb_strimwidth($text, 0, $limit, '', 'UTF-8');
            $appendEnd = true;
        } else {
            $appendEnd = false;
        }

        // Convert to HTML.
        $html = e($text);

        // Replace padded \e references with HTML links.
        if ($keepLinks) {
            $html = preg_replace_callback(
                '/(\e+;?)/u',
                function ($matches) use (&$links, &$appendEnd) {
                    list($url, $display, $length) = array_shift($links);
                    $ref = $matches[1];
                    if (substr($ref, -1) !== ';') {
                        $display = mb_strimwidth(
                            $display,
                            0,
                            strlen($ref),
                            '',
                            'UTF-8'
                        );
                        $appendEnd = false;
                        $end = static::$end;
                    } else {
                        $end = '';
                    }
                    return '<a href="' . e($url) . '">' . e($display) .  $end .
                           '</a>';
                },
                $html
            );
        }

        // Add the end string if needed.
        if ($appendEnd) {
            $html = trim($html) . static::$end;
        }

        return $html;
    }
}
