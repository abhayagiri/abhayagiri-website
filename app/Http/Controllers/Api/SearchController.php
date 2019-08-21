<?php

namespace App\Http\Controllers\Api;

use App\Search\Pages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        return Pages::search($request->q)->get();
    }
}
