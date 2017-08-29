<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Controllers\Controller;
use App\Models\Talk;

class LinkRedirectController extends Controller
{
    public function audio(Request $request, $slug)
    {
        $talk = Talk::where('url_title', $slug)->first();
        if ($talk) {
            return redirect('/new/talks/' . $talk->id . '-' . urlencode($talk->url_title));
        } else {
            return redirect('/new/talks');
        }
    }
}
