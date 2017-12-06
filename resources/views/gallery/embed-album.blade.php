@php
$base = Lang::locale() === 'th' ? '/th' : '';
@endphp
<div style="text-align:center">
    <a href="{{ $base }}/gallery/{{ $album->id }}-{{ $album->slug }}">
        <img style="max-width:500px; max-height:500px"
            src="{{ $album->thumbnail->medium_url }}"
            alt="">
    </a>
</div>
<p>
    <a href="{{ $base }}/gallery/{{ $album->id }}-{{ $album->slug }}">
        {{ $caption ? $caption : tp($album, 'title', $lng) }}
    </a>
</p>
