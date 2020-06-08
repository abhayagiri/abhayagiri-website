<?php

namespace App;

use Parsedown;
use Embera\Embera;
use App\Models\Album;
use App\Models\Danalist;
use App\Models\Resident;
use Michelf\SmartyPants;
use App\Utilities\ValidateUrlForEmbed;
use Illuminate\Support\Facades\Config;
use League\HTMLToMarkdown\HtmlConverter;

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
    public function __construct($lng)
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
            } elseif ($macro === 'residents') {
                return $this->macroResidentAll();
            } elseif ($macro === 'resident') {
                return $this->macroResidentSingle($args);
            } elseif ($macro === 'danalist') {
                return $this->macroDanalist();
            }
        }
    }

    protected function macroEmbed($url, $args)
    {
        if ($result = ValidateUrlForEmbed::forGallery($url)) {
            return $this->embedAlbum($result, $args);
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
                'handler' => 'noEscaping',
            ],
        ];
    }

    protected function embedVideo($url)
    {
        $embera = new Embera([
            // Don't send http queries
            'fake_responses' => Embera::ONLY_FAKE_RESPONSES,
            'width' => 560,
            'height' => 315,
        ]);
        $html = $embera->autoEmbed($url);

        if (substr($html, 0, 8) === '<iframe ') {
            $html = '<iframe class="embed-responsive-item" ' . substr($html, 8);

            return [
                'element' => [
                    'name' => 'div',
                    'text' => $html,
                    'handler' => 'noEscaping',
                    'attributes' => [
                        'class' => 'embed-responsive embed-responsive-16by9',
                    ],
                ],
            ];
        }
    }

    protected function macroDanalist()
    {
        return [
            'element' => [
                'name' => 'div',
                'handler' => 'noEscaping',
                'text' => Danalist::getMacroHtml($this->lng),
            ],
        ];
    }

    protected function macroResidentAll()
    {
        return [
            'element' => [
                'name' => 'div',
                'handler' => 'noEscaping',
                'text' => Resident::getMacroAllHtml($this->lng),
            ],
        ];
    }

    protected function macroResidentSingle($id)
    {
        return [
            'element' => [
                'name' => 'div',
                'handler' => 'noEscaping',
                'text' => Resident::getMacroSingleHtml($id, $this->lng),
            ],
        ];
    }

    protected function noEscaping($text, $nonNestables)
    {
        // TODO: look into making this more robust for generic handling
        return $text;
    }
}

class Markdown
{
    protected static $cleanCharsMap = [
        '/(‘|’|‚|‛)/' => "'",
        '/—/' => '---',
        '/–/' => '--',
        '/…/' => '...',
        '/(“|”|„|‟)/' => '"',
    ];

    /**
     * Convert Markdown to HTML.
     *
     * @param string $html
     * @param string $lng
     * @param mixed $markdown
     *
     * @return string
     */
    public static function toHtml($markdown, $lng = 'en')
    {
        $html = (new MyParsedown($lng))
            ->text($markdown);
        $parser = new SmartyPants;
        $parser->do_dashes = 2; // en and em-dashes
        $html = $parser->transform($html);
        // Convert tables to striped tables
        $html = preg_replace('/<table>/', '<table class="table table-striped">', $html);
        return $html;
    }

    /**
     * Convert HTML to Markdown.
     *
     * @param string $html
     * @param mixed $allowedTags
     *
     * @return string
     */
    public static function fromHtml($html, $allowedTags = '')
    {
        $converter = new HtmlConverter(['strip_tags' => true]);
        $markdown = $converter->convert($html);
        $markdown = static::cleanChars($markdown);
        $markdown = preg_replace('/ ?\. \. \. ?/', '...', $markdown);
        $markdown = preg_replace(
            '/\(https?:\/\/(www\.)?abhayagiri\.org/',
            '(',
            $markdown
        );
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

    /**
     * Return markdown cleaned of internal links and stylized characters.
     *
     * @param string $markdown
     *
     * @return string
     */
    public static function clean(string $markdown): string
    {
        return static::cleanChars(static::cleanInternalLinks($markdown));
    }

    /**
     * Return markdown cleaned of stylized characters.
     *
     * @param string $markdown
     *
     * @return string
     */
    public static function cleanChars(string $text): string
    {
        return preg_replace(
            array_keys(static::$cleanCharsMap),
            array_values(static::$cleanCharsMap),
            $text
        );
    }

    /**
     * Return markdown cleaned of internal links.
     *
     * @param string $markdown
     *
     * @return string
     */
    public static function cleanInternalLinks(string $markdown): string
    {
        $markdown = preg_replace(
            '_\[([^\]]*)\]\(https?://(?:www\.|staging\.|)abhayagiri\.org(/[^)]*)\)_',
            '[\1](\2)',
            $markdown
        );
        $markdown = preg_replace(
            '_\[([^\]]*)\]\(https?://[^/]+\.digitaloceanspaces\.com(/media/[^)]*)\)_',
            '[\1](\2)',
            $markdown
        );

        return $markdown;
    }

    /**
     * Return markdown cleaned of internal links.
     *
     * @param string $markdown
     *
     * @return string
     */
    public static function expandMediaLinks(string $markdown): string
    {
        $mediaBaseUrl = Config::get('filesystems.disks.spaces.url');

        if ($mediaBaseUrl) {
            $markdown = preg_replace(
                '_\[([^\]]*)\]\((/media/[^)]*)\)_',
                '[\1](' . $mediaBaseUrl . '\2)',
                $markdown
            );
        }

        return $markdown;
    }
}
