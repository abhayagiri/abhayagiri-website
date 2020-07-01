<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ResidentController extends Controller
{
    /**
     * Return an image response for the resident.
     *
     * @param  \App\Models\Resident  $resident
     * @param  string  $preset
     * @param  string  $format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function image(Resident $resident, string $preset, string $format): Response
    {
        $this->authorize('view', $resident);
        return app('imageCache')->getModelImageResponse($resident, $preset, $format);
    }
}
