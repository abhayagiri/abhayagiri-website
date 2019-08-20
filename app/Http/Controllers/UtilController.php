<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Util;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class UtilController extends Controller
{
    public function error(Request $request)
    {
        if ($request->input('exception')) {
            throw new Exception('Test exception');
        }
        $code = (int) $request->input('code');
        $code = in_array($code, [401, 403, 404, 419, 429, 500, 503]) ?
                $code : 500;
        abort($code);
    }

    public function version(Request $request)
    {
        $app = app();
        $data = [
            'gitRevision' => Util::gitRevision(),
            'gitMessage' => Util::gitMessage(),
            'basePath' => base_path(),
            'realBasePath' => realpath(base_path()),
            'opcache' => [
                'use_cwd' => ini_get('opcache.use_cwd'),
                'revalidate_path' => ini_get('opcache.revalidate_path'),
                'revalidate_freq' => ini_get('opcache.revalidate_freq'),
            ],
        ];
        return json_encode($data);
    }
}
