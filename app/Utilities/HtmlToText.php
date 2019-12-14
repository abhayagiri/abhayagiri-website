<?php

namespace App\Utilities;

use Html2Text\Html2Text;

/**
 * A local extension to Michael Tibben's html2text library.
 *
 * See comments below for information on changes.
 *
 * @see Html2Text
 * @see https://github.com/mtibben/html2text
 */
class HtmlToText extends Html2Text
{
    /**
     * Default configuration options.
     *
     * @type array
     */
    protected $options = [
        'do_links' => 'none',   // 'none'
                                // 'inline' (show links inline)
                                // 'nextline' (show links on the next line)
                                // 'table' (if a table of link URLs should be listed after the text.
                                // 'bbcode' (show links as bbcode)
        'markdown' => false,    //  Set this to true to format text markdown-style
                                //  <i>, <em>, <dt>, <li> and <hr> with tabs and indents.
        'uppercase' => false,   //  Set this to true to uppercase <b>,
                                //  <strong>, <th>, and <h*>.
        'width' => 0,           //  Maximum width of the formatted text, in columns.
                                //  Set this value to 0 (or less) to ignore word wrapping
                                //  and not constrain text to a fixed-width column.
    ];

    /**
     * List of preg* regular expression patterns to search for, used in
     * conjunction with $replace.
     *
     * This is duplicated in case the parent class is updated.
     *
     * @type array
     * @see $replace
     */
    protected $simpleSearch = [
        "/\r/",                                           // Non-legal carriage return
        "/[\n\t]+/",                                      // Newlines and tabs
        '/<head\b[^>]*>.*?<\/head>/i',                    // <head>
        '/<script\b[^>]*>.*?<\/script>/i',                // <script>s -- which strip_tags supposedly has problems with
        '/<style\b[^>]*>.*?<\/style>/i',                  // <style>s -- which strip_tags supposedly has problems with
        '/<i\b[^>]*>(.*?)<\/i>/i',                        // <i>
        '/<em\b[^>]*>(.*?)<\/em>/i',                      // <em>
        '/(<ul\b[^>]*>|<\/ul>)/i',                        // <ul> and </ul>
        '/(<ol\b[^>]*>|<\/ol>)/i',                        // <ol> and </ol>
        '/(<dl\b[^>]*>|<\/dl>)/i',                        // <dl> and </dl>
        '/<li\b[^>]*>(.*?)<\/li>/i',                      // <li> and </li>
        '/<dd\b[^>]*>(.*?)<\/dd>/i',                      // <dd> and </dd>
        '/<dt\b[^>]*>(.*?)<\/dt>/i',                      // <dt> and </dt>
        '/<li\b[^>]*>/i',                                 // <li>
        '/<hr\b[^>]*>/i',                                 // <hr>
        '/<div\b[^>]*>/i',                                // <div>
        '/(<table\b[^>]*>|<\/table>)/i',                  // <table> and </table>
        '/(<tr\b[^>]*>|<\/tr>)/i',                        // <tr> and </tr>
        '/<td\b[^>]*>(.*?)<\/td>/i',                      // <td> and </td>
        '/<span class="_html2text_ignore">.+?<\/span>/i', // <span class="_html2text_ignore">...</span>
        '/<(img)\b[^>]*alt=\"([^>"]+)\"[^>]*>/i',         // <img> with alt tag
    ];

    /**
     * List of pattern replacements corresponding to patterns searched.  This
     * is used when $options['markdown'] is false.
     *
     * @type array
     * @see $search
     */
    protected $simpleReplace = [
        '',                              // Non-legal carriage return
        ' ',                             // Newlines and tabs
        '',                              // <head>
        '',                              // <script>s -- which strip_tags supposedly has problems with
        '',                              // <style>s -- which strip_tags supposedly has problems with
        '\\1',                           // <i>
        '\\1',                           // <em>
        "\n\n",                          // <ul> and </ul>
        "\n\n",                          // <ol> and </ol>
        "\n\n",                          // <dl> and </dl>
        "\\1\n",                         // <li> and </li>
        "\\1\n",                         // <dd> and </dd>
        "\\1",                           // <dt> and </dt>
        "\n",                            // <li>
        "\n\n",                          // <hr>
        "<div>\n",                       // <div>
        "\n\n",                          // <table> and </table>
        "\n",                            // <tr> and </tr>
        "\\1\n",                         // <td> and </td>
        "",                              // <span class="_html2text_ignore">...</span>
        '[\\2]',                         // <img> with alt tag
    ];

    /**
     * Override parent converter() to handle $options['markdown'].
     *
     * @param  string &$text
     * @return void
     */
    protected function converter(&$text)
    {
        if ($this->options['markdown']) {
            parent::converter($text);
        } else {
            try {
                $origSearch = $this->search;
                $origReplace = $this->replace;
                $this->search = $this->simpleSearch;
                $this->replace = $this->simpleReplace;
                parent::converter($text);
                $text = preg_replace([
                    '/ {2,}/u',  // Replace two or more spaces with a single space
                    '/ *\n */u', // Remove spaces at the beginning and end of lines
                    '/\n{3,}/u', // Replace three or more newlines with two new lines
                ], [
                    ' ',
                    "\n",
                    "\n\n",
                ], $text);
                $text = trim($text);
            } finally {
                $this->search = $origSearch;
                $this->replace = $origReplace;
            }
        }
    }

    /**
     * Only call parent toupper() if options['uppercase'] is true.
     *
     * @param  string $str
     * @return string
     */
    protected function toupper($str)
    {
        if ($this->options['uppercase']) {
            return parent::toupper($str);
        } else {
            return $str;
        }
    }

    /**
     * Convinience wrapper.
     */
    public static function toText($html = '', $options = [])
    {
        $self = new static($html, $options);
        return $self->getText();
    }
}
