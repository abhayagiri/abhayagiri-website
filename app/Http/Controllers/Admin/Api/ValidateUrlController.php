<?php

namespace App\Http\Controllers\Admin\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Utilities\ValidateUrlForEmbed;
use App\Http\Controllers\ApiController;

class ValidateUrlController extends ApiController
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
            'valid' => ValidateUrlForEmbed::validate($request->url),
        ], Response::HTTP_OK);
    }
}
