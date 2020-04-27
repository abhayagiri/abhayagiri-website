<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class GalleryController extends Controller
{
    /**
     * Display the new proxy.
     *
     * @return \Illuminate\Http\View
     */
    public function index(): View
    {
        return view('app.new-proxy');
    }
}
