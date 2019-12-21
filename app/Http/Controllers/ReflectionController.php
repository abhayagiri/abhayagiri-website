<?php

namespace App\Http\Controllers;

use App\Models\Reflection;
use Illuminate\View\View;

class ReflectionController extends Controller
{
    /**
     * Display a listing of reflections.
     *
     * @return \Illuminate\Http\View
     */
    public function index(): View
    {
        $this->authorize('viewAny', Reflection::class);
        $reflections = Reflection::public()->postOrdered()->paginate(10);
        return view('reflections.index', ['reflections' => $reflections]);
    }

    /**
     * Display the specified reflection.
     *
     * @param \App\Models\Reflection $reflection
     * @return \Illuminate\View\View
     */
    public function show(Reflection $reflection): View
    {
        $this->authorize('view', $reflection);
        return view('reflections.show', ['reflection' => $reflection]);
    }
}
