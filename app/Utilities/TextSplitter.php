<?php

declare(strict_types=1);

namespace App\Utilities;

/**
 * This is a utility class to split strings into segments while maintaining a
 * minimum and maximum length of each segment.
 */
class TextSplitter
{
    const DEFAULT_MAX = 1000;
    const DEFAULT_MIN = 0;

    /**
     * The maximum number of characters for each segment (guaranteed).
     *
     * @var int
     */
    public $max;

    /**
     * The minimum number of characters for each segment (best effort).
     *
     * @var int
     */
    public $min;

    /**
     * If true, each segment as short as possible (close to $min).
     *
     * If false (default), each segment will be as long as possible (close to
     * $max).
     *
     * @var int
     */
    public $short;

    /**
     * The preg matcher to split each paragraph segment.
     *
     * @see splitByParagraph()
     *
     * @var string
     */
    protected $paragraphSplitter = '/\s*\n(?:\s*\n)+\s*/u';

    /**
     * The preg matcher to split each word segment.
     *
     * @see splitBySpaces()
     *
     * @var string
     */
    protected $wordSplitter = "/\s+/u";

    /**
     * Object constructor.
     *
     * @param  int  $min
     * @param  int  $max
     * @param  bool  $short
     */
    public function __construct(
        int $max = self::DEFAULT_MAX,
        int $min = self::DEFAULT_MIN,
        bool $short = false
    ) {
        if ($max <= $min) {
            throw new \InvalidArgumentException("max $max <= min $min");
        }
        if ($min < 0) {
            throw new \InvalidArgumentException("min $min < 0");
        }
        $this->min = $min;
        $this->max = $max;
        $this->short = $short;
    }

    /**
     * Return $text bisected at $max (or $min if $short is true).
     *
     * @see $max
     * @see $min
     * @see $short
     *
     * @param  string  $text
     *
     * @return array
     */
    public function bisect(string $text): array
    {
        if ($this->short) {
            $pos = max(1, $this->min);
        } else {
            $pos = min(mb_strlen($text, 'UTF-8'), $this->max);
        }
        return [
            mb_substr($text, 0, $pos, 'UTF-8'),
            mb_substr($text, $pos, null, 'UTF-8'),
        ];
    }

    /**
     * Split $text by paragraphs satsifying $min, $max and $short.
     *
     * If $limit is set, this will return no more than $limit segments.
     *
     * @see $paragraphSplitter
     *
     * @param  string  $text
     * @param  int  $limit
     *
     * @return array
     */
    public function splitByParagraphs(
        string $text,
        ?int $limit = null
    ): array {
        $fallback = function ($text) {
            return $this->splitByWords($text, 2);
        };
        return $this->splitByPattern(
            $this->paragraphSplitter,
            trim($text),
            $limit,
            $fallback
        );
    }

    /**
     * Split $text by words satsifying $min, $max and $short.
     *
     * If $limit is set, this will return no more than $limit segments.
     *
     * @see $wordSplitter
     *
     * @param  string  $text
     * @param  int  $limit
     *
     * @return array
     */
    public function splitByWords(
        string $text,
        ?int $limit = null
    ): array {
        $fallback = function ($text) {
            return array_map('trim', $this->bisect($text));
        };
        return $this->splitByPattern(
            $this->wordSplitter,
            trim($text),
            $limit,
            $fallback
        );
    }

    /**
     * Split $text by $pattern satisfying $min, $max and $short.
     *
     * If $limit is set, this will return no more than $limit segments.
     *
     * If $fallback is set, then if this cannot split and satisify $min and
     * $max, $fallback will be called. Otherwise bisect() will be used.
     *
     * @param  string  $pattern
     * @param  string  $text
     * @param  int  $limit
     * @param  callable  $fallback
     *
     * @return array
     */
    protected function splitByPattern(
        string $pattern,
        string $text,
        ?int $limit = null,
        ?callable $fallback = null
    ): array {
        $segments = [];

        while ($text !== '' && ($limit < 1 || sizeof($segments) < $limit - 1)) {
            $length = mb_strlen($text, 'UTF-8');
            if (!$this->short && $length <= $this->max) {
                break;
            }

            $splits = $this->getSplitCandidates($pattern, $text);

            //dump([$text, $pattern, $splits]);

            if (empty($splits)) {
                if ($length <= $this->max) {
                    $left = $text;
                    $text = '';
                } elseif ($fallback) {
                    list($left, $text) = call_user_func($fallback, $text);
                } else {
                    list($left, $text) = $this->bisect($text);
                }
            } else {
                $index = $this->short ? 0 : (sizeof($splits) - 1);
                $left = $splits[$index][1];
                $text = $splits[$index][2];
            }
            $segments[] = $left;
        }

        if ($text !== '') {
            $segments[] = $text;
        }
        return $segments;
    }

    /**
     * Return an array of split candidates where the length of each candidate
     * is between a minimum and maximum.
     *
     * The return value is an array of arrays, where the subarray's members are:
     *
     *     [0]: the length of the left part (of the splittted string)
     *     [1]: the left part
     *     [2]: the right part
     *
     * @see $min
     * @see $max
     *
     * @param  string  $pattern
     * @param  string  $text
     *
     * @return array
     */
    protected function getSplitCandidates(string $pattern, string $text): array
    {
        preg_match_all($pattern, $text, $matches, PREG_OFFSET_CAPTURE);
        $splits = [];
        foreach ($matches[0] as $match) {
            $pos = $match[1];
            if ($pos < $this->min) {
                continue;
            }
            $left = substr($text, 0, $pos);
            $leftLen = mb_strlen($left, 'UTF-8');
            if ($leftLen > $this->max) {
                break;
            } elseif ($leftLen >= $this->min) {
                $rightPos = $leftLen + mb_strlen($match[0], 'UTF-8');
                $right = mb_substr($text, $rightPos, null, 'UTF-8');
                $splits[] = [$leftLen, $left, $right];
            }
        }
        return $splits;
    }
}
