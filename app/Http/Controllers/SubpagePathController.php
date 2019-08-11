<?php

namespace App\Http\Controllers;

use App\Models\Subpage;
use Illuminate\View\View;

class SubpagePathController extends Controller
{
    /**
     * Show a subpage from a path.
     *
     * @param string $path
     * @return Illuminate\View\View
     */
    public function __invoke(string $path) : View
    {
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
