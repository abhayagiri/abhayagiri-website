<?php

namespace App\Http\Controllers;

use App\Models\Tale;
use Illuminate\View\View;

class TaleController extends Controller
{
    /**
     * Display a listing of tales.
     *
     * @return \Illuminate\Http\View
     */
    public function index(): View
    {
        $this->authorize('viewAny', Tale::class);
        $tales = Tale::public()->postOrdered()->paginate(10);
        return view('tales.index', ['tales' => $tales]);
    }

    /**
     * Display the specified tale.
     *
     * @param \App\Models\Tale $tale
     *
     * @return \Illuminate\View\View
     */
    public function show(Tale $tale): View
    {
        $this->authorize('view', $tale);
        return view('tales.show', ['tale' => $tale]);
    }
}
