<?php

namespace App\Utilities;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

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
    public static function localizedPath(string $path, string $lng = null)
                                         : string
    {
        $lng = $lng ?: Lang::locale();
        $path = trim($path, '/');
        if ($path === '' || $path === 'th') {
            return $lng === 'en' ? '/' : '/th';
        }
        if (Str::startsWith($path, 'th/')) {
            $path = substr($path, 3);
        }
        return ($lng === 'en' ? '/' : '/th/') . $path;
    }
}
