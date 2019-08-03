<?php

namespace App\Http\Controllers;

use App\Models\Subpage;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubpageController extends Controller
{
    /**
     * Show a subpage from an id.
     *
     * @param int $id
     * @return View
     */
    public function show($id) : View
    {
        $subpage = Subpage::public()
            ->findOrFail((int) $id);
        return view('subpage.show', ['subpage' => $subpage]);
    }

    /**
     * Show a subpage from a path.
     *
     * @param string$path
     * @return View
     */
    public function showFromPath(string $path) : View
    {
        $parts = explode('/', $path, 2);
        if (sizeof($parts) == 1) {
            $subpage = Subpage::public()
                ->where('page', $parts[0])
                ->orderBy('rank')
                ->firstOrFail();
        } else {
            $subpage = Subpage::public()
                ->where('page', $parts[0])
                ->where('subpath', $parts[1])
                ->firstOrFail();
        }
        return view('subpage.show', ['subpage' => $subpage]);
    }
}
