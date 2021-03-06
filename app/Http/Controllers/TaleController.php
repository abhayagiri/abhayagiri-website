<?php

namespace App\Http\Controllers;

use App\Models\Tale;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class TaleController extends Controller
{
    /**
     * Return an image response for the tale.
     *
     * @param  \App\Models\Tale  $tale
     * @param  string  $preset
     * @param  string  $format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function image(Tale $tale, string $preset, string $format): Response
    {
        $this->authorize('view', $tale);
        return app('imageCache')->getModelImageResponse($tale, $preset, $format);
    }

    /**
     * Display a listing of tales.
     *
     * @return \Illuminate\Http\View
     */
    public function index(): View
    {
        $this->authorize('viewAny', Tale::class);
        return view('tales.index')
            ->withTales(Tale::public()->commonOrder()->paginate(10));
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
        return view('tales.show')
            ->withTale($tale)
            ->withAssociated($tale->getAssociated(10));
    }
}
