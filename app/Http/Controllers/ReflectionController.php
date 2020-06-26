<?php

namespace App\Http\Controllers;

use App\Models\Reflection;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ReflectionController extends Controller
{
    /**
     * Return an image response for the reflection.
     *
     * @param  \App\Models\Reflection  $reflection
     * @param  string  $preset
     * @param  string  $format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function image(Reflection $reflection, string $preset, string $format): Response
    {
        $this->authorize('view', $reflection);
        return app('imageCache')->getModelImageResponse($reflection, $preset, $format);
    }

    /**
     * Display a listing of reflections.
     *
     * @return \Illuminate\Http\View
     */
    public function index(): View
    {
        $this->authorize('viewAny', Reflection::class);
        return view('reflections.index')
            ->withReflections(Reflection::public()->commonOrder()->paginate(10));
    }

    /**
     * Display the specified reflection.
     *
     * @param \App\Models\Reflection $reflection
     *
     * @return \Illuminate\View\View
     */
    public function show(Reflection $reflection): View
    {
        $this->authorize('view', $reflection);
        return view('reflections.show')
            ->withReflection($reflection)
            ->withAssociated($reflection->getAssociated(10));
    }
}
