<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Symfony\Component\HttpFoundation\Response;

class AuthorController extends Controller
{
    /**
     * Return an image response for the author.
     *
     * @param  \App\Models\Author  $author
     * @param  string  $preset
     * @param  string  $format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function image(Author $author, string $preset, string $format): Response
    {
        $this->authorize('view', $author);
        return app('imageCache')->getModelImageResponse($author, $preset, $format);
    }
}
