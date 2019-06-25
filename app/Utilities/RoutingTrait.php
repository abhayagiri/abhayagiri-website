<?php

namespace App\Utilities;

use Illuminate\Support\Facades\Lang;

trait RoutingTrait
{
    /**
     * Returns the locale-specific path, i.e., prefixed by '/th' if in 'th'
     * locale.
     *
     * @param string $path
     * @param string $lng  (Optional)
     * @return string
     */
    public static function localizedPath(string $path, string $lng = null) : string {
        $lng = $lng ?: Lang::locale();
        if (!$path || $path === '/') {
            return $lng === 'en' ? '/' : '/th';
        } else {
            return ($lng === 'en' ? '' : '/th') .
                   ($path[0] === '/' ? '' : '/') .
                   $path;
        }
    }
}
