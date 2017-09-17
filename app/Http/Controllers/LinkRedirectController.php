<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Controllers\Controller;
use App\Models\Redirect;
use App\Models\Talk;

class LinkRedirectController extends Controller
{
    public function redirect(Request $request, $slug)
    {
        $path = $request->path();
        $redirect = Redirect::getRedirectFromPath($path);
        if ($redirect) {
            return redirect($redirect);
        } else if ($request->is('audio/*')) {
            return redirect('/new/talks');
        } else if ($request->is('th/audio/*')) {
            return redirect('/new/th/talks');
        } else {
            return redirect('/');
        }
    }
}
