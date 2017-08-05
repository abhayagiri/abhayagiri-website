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

    protected function resolveMediaPath($path)
    {
        if (!$path) {
            return null;
        }
        $mediaPath = Path::resolve(public_path('media'));
        if (substr($path, 0, 6) === 'media/') {
            $fullPath = Path::resolve($mediaPath, substr($path, 6));
        } else {
            $fullPath = Path::resolve($mediaPath, $path);
        }
        if ($fullPath && Path::isInside($fullPath, $mediaPath)) {
            if (!File::exists($fullPath)) {
                Log::warning($path . 'does not exist');
            }
            return Path::relative($mediaPath, $fullPath);
        } else {
            throw new \Exception($path . ' not in ' . $mediaPath);
        }
    }
}
