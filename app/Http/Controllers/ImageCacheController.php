<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ImageCacheController extends Controller
{
    /**
     * Return a resized or cached image.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $path
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function image(Request $request, string $path): Response
    {
        $params = ['fm' => 'pjpg'];
        if ($request->has('w')) {
            $params['w'] = min(max(intval($request->input('w')), 8), 2048);
        }
        if ($request->has('h')) {
            $params['h'] = min(max(intval($request->input('h')), 8), 2048);
        }
        return app('imageCache')->getImageResponse($path, $params);
    }
}
