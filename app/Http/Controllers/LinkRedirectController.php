<?php

namespace App\Http\Controllers;

use App\Models\Redirect;

use Illuminate\Http\Request;

class LinkRedirectController extends Controller
{
    public function redirect(Request $request, $slug)
    {
        $path = $request->path();
        $redirect = Redirect::getRedirectFromPath($path);
        if ($redirect) {
            return redirect($redirect);
        } elseif ($request->is('audio/*')) {
            return redirect('/talks');
        } elseif ($request->is('th/audio/*')) {
            return redirect('/th/talks');
        } else {
            return redirect('/');
        }
    }
}
