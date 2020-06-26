<div class="gallery-embed">
    <a href="{{ $album->path }}" class="lightbox"
        data-gallery-id="{{ $album->id }}"
        data-gallery-index="0">
        <img src="{{ $album->thumbnail->large_url }}"
             alt="{{ tp($album->thumbnail, 'caption') }}">
    </a>
    <p>
        <a href="{{ $album->path }}">
            {{ $caption ? $caption : tp($album, 'title') }}
        </a>
    </p>
</div>
