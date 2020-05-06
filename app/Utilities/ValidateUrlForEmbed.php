<?php

namespace App\Utilities;

class ValidateUrlForEmbed
{
    /**
     * Determine if a given url can be in general be embedded.
     *
     * @param string $url
     *
     * @return bool
     */
    public static function validate(string $url)
    {
        if (static::forYouTube($url)) {
            return true;
        }

        if (static::forGallery($url)) {
            return true;
        }

        if (static::forTalk($url)) {
            return true;
        }

        return false;
    }

    /**
     * Determine if a given YouTube url can be embedded.
     *
     * @param mixed $url
     *
     * @return string|bool
     */
    public static function forYouTube($url)
    {
        if (strpos($url, 'youtu') !== false) {
            preg_match(
                '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i',
                $url,
                $matches
            );

            return $matches[1] ?? $url;
        }

        return false;
    }

    /**
     * Determine if a given Gallery url can be embedded.
     *
     * @param mixed $url
     *
     * @return string|bool
     */
    public static function forGallery($url)
    {
        if (strpos($url, 'gallery') !== false) {
            preg_match('_^(?:https?://.+)?(?:/th)?/gallery/(.+)$_', $url, $matches);

            return $matches[1] ?? $url;
        }

        return false;
    }

    /**
     * Determine if a given Talk url can be embedded.
     *
     * @param mixed $url
     *
     * @return string|bool
     */
    public static function forTalk($url)
    {
        if (strpos($url, 'talks') !== false) {
            preg_match('_^(?:https?://.+)?(?:/th)?/talks/(.+)$_', $url, $matches);

            return $matches[1] ?? $url;
        }

        return false;
    }
}
