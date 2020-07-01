<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\View\View;

class GalleryController extends Controller
{
    /**
     * Display a listing of albums.
     *
     * @return \Illuminate\Http\View
     */
    public function index(): View
    {
        $this->authorize('viewAny', Album::class);
        $albums = Album::commonOrder()
                       ->with(['thumbnail'])
                       ->paginate(12);
        return view('gallery.index')->withAlbums($albums);
    }


    /**
     * Display the specified album.
     *
     * @param \App\Models\Album $album
     *
     * @return \Illuminate\View\View
     */
    public function show(Album $album): View
    {
        $this->authorize('view', $album);
        return view('gallery.show')
            ->withAlbum($album)
            ->withAssociated($album->getAssociated(12));
    }
}
