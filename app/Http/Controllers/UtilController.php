<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Util;

class UtilController extends Controller
{
    public function error(Request $request)
    {
        return view('error');
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
