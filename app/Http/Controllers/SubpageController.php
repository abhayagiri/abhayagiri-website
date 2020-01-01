<?php

namespace App\Http\Controllers;

use App\Models\Subpage;
use Illuminate\View\View;

class SubpageController extends Controller
{
    /**
     * Show a subpage.
     *
     * @param App\Models\Subpage $subpage
     *
     * @return Illuminate\View\View
     */
    public function show(Subpage $subpage) : View
    {
        $this->authorize('view', $subpage);
        return view('subpages.show', ['subpage' => $subpage]);
    }
}
