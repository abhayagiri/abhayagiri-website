<?php

namespace App\Http\Controllers;

use App\Models\Redirect;
use App\Models\Resident;
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
        $parts = explode('/', $path, 2);
        $query = Subpage::where('page', $parts[0]);
        if (sizeof($parts) == 1) {
            $subpage = $query->orderBy('rank')->firstOrFail();
        } else {
            $subpage = $query->where('subpath', $parts[1])->firstOrFail();
        }
        $this->authorize('view', $subpage);
        return view('subpages.show', ['subpage' => $subpage]);
    }
}
