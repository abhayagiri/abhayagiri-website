<?php

namespace App\Http\Requests;

use Backpack\CRUD\app\Http\Requests\CrudRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Weevers\Path\Path;

class AppCrudRequest extends CrudRequest
{
    public function validate()
    {
        $this->sanitize();
        parent::validate();
    }

    public function sanitize()
    {
        // Nothing.
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
     * @return string
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
            throw new \Exception($path . ' not in ' . $mediaPath);
        }
    }
}
