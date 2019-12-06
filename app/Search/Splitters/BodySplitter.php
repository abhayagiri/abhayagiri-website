<?php

namespace App\Search\Splitters;

use Algolia\ScoutExtended\Contracts\SplitterContract;
use App\Contracts\SplitterServiceContract;

class BodySplitter implements SplitterContract
{
    /**
     * @var int Maximum length of each split chunk.
     */
    protected $chunkLength = 2000;

    /**
     * @var App\Contracts\SplitterServiceContract
     */
    protected $service;

    /**
     * Creates a new instance of the class.
     *
     * @param  App\Contracts\SplitterServiceContract $service
     *
     * @return void
     */
    public function __construct(SplitterServiceContract $service)
    {
         $this->service = $service;
    }

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
        return static::chunkText($value, $this-chunkLength);
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
