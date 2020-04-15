<?php

namespace App\Utilities;

trait EncodingTrait
{
    /**
     * Escape text for a MySQL Like Query.
     *
     * @param string $text
     *
     * @return string
     */
    public static function escapeLikeQueryText(string $text): string
    {
        return str_replace(['%', '_'], ['\%', '\_'], $text);
    }

    /**
     * URL encode a path except for path separators.
     *
     * @see https://stackoverflow.com/questions/2834524/urlencode-except
     *
     * @param  string  $path
     *
     * @return string
     */
    public static function urlEncodePath(string $path): string
    {
        return implode('/', array_map('rawurlencode', explode('/', $path)));
    }
}
