<?php

namespace App\Http\Controllers\Api;

use App\Search\Pages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;

class SearchController extends Controller
{
    /**
     * Perform an index search.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Support\Collection
     */
    public function __invoke(Request $request)
    {
        Lang::setLocale($request->language ?? 'en');

        return Pages::search($request->q)->get();
    }
}
