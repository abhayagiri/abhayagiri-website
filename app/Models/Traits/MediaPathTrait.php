<?php

namespace App\Models\Traits;

use App\Util;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Weevers\Path\Path;

trait MediaPathTrait
{
    /**
     * Returns a path fragment from an attribute.
     *
     * @param string $name
     *
     * @return string or null
     */
    protected function getMediaPathFrom($name)
    {
        $value = $this->getAttribute($name);
        // TODO prepend config('app.url') ?
        return $this->getMediaPathFromRawValue($value);
    }

    /**
     * Returns a path fragment from a raw value.
     *
     * @param string $value
     *
     * @return string or null
     */
    protected function getMediaPathFromRawValue($value)
    {
        return $value ? '/media/' . Util::urlEncodePath($value) : null;
    }

    /**
     * Returns a URL from an attribute.
     *
     * @param string $name
     *
     * @return string or null
     */
    protected function getMediaUrlFrom($name)
    {
        $value = $this->getMediaPathFrom($name);
        return $value ? URL::to($value) : null;
    }

    /**
     * Safely set the media path.
     *
     * @param string $name
     * @param mixed $value
     *
     * @return void
     */
    public function setMediaPathAttributeTo($name, $value)
    {
        $this->attributes[$name] = $this->resolveMediaPath($value);
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
     *
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
