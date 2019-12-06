<?php

namespace App\Search\Splitters;

use Algolia\ScoutExtended\Contracts\SplitterContract;

class BodySplitter implements SplitterContract
{
    /**
     * @var int Maximum length of each split chunk.
     */
    protected $chunkLength = 2000;

    /**
     * Splits the given value.
     *
     * @param  object $searchable
     * @param  mixed $value
     *
     * @return array
     */
    public function split($searchable, $value): array
    {
        return static::chunkText($value, $this->chunkLength);
    }

    /**
     * Chunk $text into an array where each segment is no more than $length
     * unicode characters.
     *
     * @param string $text
     * @param int $length
     *
     * @return array
     */
    public static function chunkText($text, $length)
    {
        return array_map('join', array_chunk(
            preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY), $length));
    }
}
