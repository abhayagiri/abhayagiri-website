<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Weevers\Path\Path;

trait MediaPathTrait
{
    /**
     * Returns a URL from a media path.
     *
     * @param string $name
     * @return string or null
     */
    protected function getMediaUrlFrom($name)
    {
        $value = $this->getAttribute($name);
        // TODO prepend config('app.url') ?
        return $value ? '/media/' . $this->encodeMediaPath($value) : null;
    }

    /**
     * Safely set the media path.
     *
     * @param string $name
     * @param mixed $value
     * @return string or null
     */
    public function setMediaPathAttributeTo($name, $value)
    {
        $this->attributes[$name] = $this->resolveMediaPath($value);
    }

    /**
     * Encodes filename parts using rawurlencode.
     *
     * @param string $path
     * @return string
     */
    protected function encodeMediaPath($path)
    {
        return implode('/', array_map('rawurlencode', explode('/', $path)));
    }

    /**
     * Fixes and resolves $path for use with Elfinder.
     *
     * Elfinder (as currently configured) will return paths relative to public.
     * We want to adjust this so that it returns the path relative to media.
     *
     * If $subdir is specified, this will return relative to media/$subdir
     * instead of just media.
     *
     * @param string $path
     * @param string $basePath
     * @return string or null
     */
    protected function resolveMediaPath($path, $subdir = null)
    {
        if (!$path) {
            return null;
        }
        $mediaPath = Path::resolve(public_path('media'));
        if ($subdir) {
            $basePath = Path::resolve($mediaPath, $subdir);
        } else {
            $basePath = $mediaPath;
        }
        if (substr($path, 0, 6) === 'media/') {
            $fullPath = Path::resolve($mediaPath, substr($path, 6));
        } else {
            $fullPath = Path::resolve($basePath, $path);
        }
        if ($fullPath && Path::isInside($fullPath, $mediaPath)) {
            if (!File::exists($fullPath)) {
                Log::warning($path . 'does not exist');
            }
            return Path::relative($basePath, $fullPath);
        } else {
            Log::warning($path . ' not in ' . $mediaPath);
            return null;
        }
    }
}
