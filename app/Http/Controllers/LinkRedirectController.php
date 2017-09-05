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
        $redirect = Redirect::where('from', $path)->first();
        if ($redirect) {
            $to = json_decode($redirect->to);
            if ($to->type === 'talks') {
                $talk = Talk::find($to->id);
                if ($talk) {
                    if ($to->lng === 'th') {
                        $redirectPath = '/new/th/talks/';
                    } else {
                        $redirectPath = '/new/talks/';
                    }
                    $redirectPath .= $talk->id . '-' . urlencode(str_slug($talk->title));
                } else {
                    $redirectPath = '/new/talks';
                }
                return redirect($redirectPath);
            }
        }
        if ($request->is('audio/*')) {
            return redirect('/new/talks');
        } else if ($request->is('th/audio/*')) {
            return redirect('/new/th/talks');
        } else {
            return redirect('/');
        }
    }
}
