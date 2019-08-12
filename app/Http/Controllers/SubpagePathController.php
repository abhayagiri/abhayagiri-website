<?php

namespace App\Http\Controllers;

use App\Models\Redirect;
use App\Models\Subpage;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SubpagePathController extends Controller
{
    /**
     * Redirect Show a subpage from a path.
     *
     * @param string $path
     * @return Response
     */
    public function __invoke(string $path)
    {
        $redirect = Redirect::getRedirectFromPath(Request::path());
        if ($redirect) {
            return redirect($redirect);
        }
        $subpage = Subpage::public()->withPath($path)->firstOrFail();
        $this->authorize('view', $subpage);
        return view('subpages.show', ['subpage' => $subpage]);
    }
}
