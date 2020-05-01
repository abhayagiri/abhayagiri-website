<?php

namespace App\Http\Controllers;

use App\Models\Talk;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class TalkController extends Controller
{
    /**
     * Return an audio redirect for the talk.
     *
     * @param  \App\Models\Talk  $talk
     * @param  string  $format
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function audio(Talk $talk, string $format): RedirectResponse
    {
        if ($format === 'mp3') {
            // TODO: redirect to the CDN
            $url = $talk->media_url;
            if ($url) {
                return redirect($url);
            }
        }
        abort(404);
    }

    /**
     * Return an image response for the talk.
     *
     * @param  \App\Models\Talk  $talk
     * @param  string  $preset
     * @param  string  $format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function image(Talk $talk, string $preset, string $format): Response
    {
        $this->authorize('view', $talk);
        return app('imageCache')->getModelImageResponse($talk, $preset, $format);
    }

    /**
     * Display the new proxy.
     *
     * @return \Illuminate\Http\View
     */
    public function index(): View
    {
        return view('app.new-proxy');
    }
}
