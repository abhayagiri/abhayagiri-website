@extends('layouts.app')

@section('main')

<section id="gallery">

    <article class="album">

        <h2>{{ $album->title }}</h2>
        <section class="description">
            <p>{{ $album->description }}</p>
        </section>

    </article>

    <section class="masonry-grid">

        @foreach ($album->photos as $i => $photo)

            @php
                $ratio = $photo->small_width / $photo->small_height;
                if ($ratio >= 1.25) {
                    $photoClass = 'landscape';
                } elseif ($ratio <= 0.80) {
                    $photoClass = 'portrait';
                } else {
                    $photoClass = 'square';
                }
            @endphp

            <div class="photo {{ $photoClass }}">

                <a href="{{ $photo->original_url }}" class="image lightbox"
                    data-gallery-id="{{ $album->id }}"
                    data-gallery-index="{{ $i }}">
                    @include('gallery.photo', [
                        'photo' => $photo,
                        'sizes' => '(max-width: 768px)  510px,' .
                                   '(max-width: 992px)  690px,' .
                                   '(max-width: 1200px) 464px,' .
                                                       '554px',
                    ])
                </a>

            </div>

        @endforeach

    </section>

    @include('app.article-links');

</section>

@endsection
