@extends('layouts.app')

@section('main')

<section id="gallery">

    <section class="album-grid">

        @foreach ($albums as $album)
            <div class="photo">
                <a href="{{ $album->path }}"
                    class="image"
                    data-gallery-id="{{ $album->id }}"
                    data-gallery-index="0">
                    @include('gallery.photo', [
                        'photo' => $album->thumbnail,
                        'sizes' => '(max-width: 768px) 510px,' .
                                   '(max-width: 992px) 690px,' .
                                                      '554px',
                    ])
                </a>
                <a href="{{ $album->path }}" class="title">
                    {{ $album->title }}
                </a>
            </div>
        @endforeach

    </section>

    @include('app.pagination', ['items' => $albums])

</section>

<gallery-manager></gallery-manager>

@endsection
