<?php

namespace App\Http\Controllers\Admin\Api;

use App\Markdown;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\ApiController;

class RenderMarkdownController extends ApiController
{
    /**
     * Handle the request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return response()->json([
            'html' => Markdown::toHtml($request->text),
        ], Response::HTTP_OK);
    }
}
