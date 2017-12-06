<?php

namespace App;

use Parsedown;
use League\HTMLToMarkdown\HtmlConverter;
use Michelf\SmartyPants;
use Illuminate\Support\Facades\Log;

use App\Models\Album;
use App\Models\DanaList;
use App\Models\Resident;

/**
 * Macros:
 *
 * [!embed](https://youtu.be/FjIcJNPvxtc)
 * [!embed](/gallery/71)
 * [!residents]
 * [!resident pasanno]
 * [!danalist]
 *
 */
class MyParsedown extends Parsedown
{

    function __construct($lng)
    {
        $this->lng = $lng;
        $this->BlockTypes['['][] = 'Macro';
        $this->setBreaksEnabled(true);
    }

    protected function inlineSpecialCharacter($excerpt)
    {
        // We handle quotes seperately.
    }

    protected function blockMacro($line)
    {
        $macroRe = '/^\s*\[\s*!\s*(.+?)(?:\s+(.+))?\s*\](?:\(\s*([^)]*)\s*\))?\s*$/i';
        if (preg_match($macroRe, $line['text'], $matches)) {
            $macro = strtolower($matches[1]);
            $args = count($matches) >= 3 ? $matches[2] : '';
            $url = count($matches) >= 4 ? $matches[3] : '';
            if ($macro === 'embed') {
                return $this->macroEmbed($url, $args);
            } else if ($macro === 'residents') {
                return $this->macroResidentAll();
            } else if ($macro === 'resident') {
                return $this->macroResidentSingle($args);
            } else if ($macro === 'danalist') {
                return $this->macroDanaList();
            }
        }
    }

    protected function macroEmbed($url, $args)
    {
        $galleryRe = '_^(?:https?://.+)?(?:/th)?/gallery/(.+)$_';
        if (preg_match($galleryRe, $url, $matches)) {
            return $this->embedAlbum($matches[1], $args);
        } else {
            return $this->embedVideo($url);
        }
    }

    protected function embedAlbum($id, $caption)
    {
        $html = Album::getMacroHtml($id, $caption, $this->lng);
        return [
            'element' => [
                'name' => 'div',
                'text' => $html,
            ],
        ];
    }

    protected function embedVideo($url)
    {
        $embera = new \Embera\Embera([
            'oembed' => false, // Don't send http queries
            'params' => [
                'width' => 560,
                'height' => 315,
            ],
        ]);
        $html = $embera->autoEmbed($url);
        if (substr($html, 0, 8) === '<iframe ') {
            $html = '<iframe class="embed-responsive-item" ' . substr($html, 8);
            return [
                'element' => [
                    'name' => 'div',
                    'text' => $html,
                    'attributes' => [
                        'class' => 'embed-responsive embed-responsive-16by9',
                    ],
                ],
            ];
        }
    }

    protected function macroDanaList()
    {
        return [
            'element' => [
                'name' => 'div',
                'text' => DanaList::getMacroHtml($this->lng),
            ],
        ];
    }

    protected function macroResidentAll()
    {
        return [
            'element' => [
                'name' => 'div',
                'text' => Resident::getMacroAllHtml($this->lng),
            ],
        ];
    }

    protected function macroResidentSingle($id)
    {
        return [
            'element' => [
                'name' => 'div',
                'text' => Resident::getMacroSingleHtml($id, $this->lng),
            ],
        ];
    }
}

class Markdown
{
    /**
     * Convert Markdown to HTML.
     *
     * @param string $html
     * @param string $lng
     * @return string
     */
    public static function toHtml($markdown, $lng = 'en')
    {
        $html = (new MyParsedown($lng))
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
