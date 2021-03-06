@php
    $abridged = \App\Util::abridge($html, $length)
@endphp
<p class="abridge-{{ $lines ?? 2 }}">
    {!! $abridged !!}
</p>
@if (isset($path) && mb_strlen($abridged, 'UTF-8') > $length)
    <p>
        <a class="btn btn-light" role="button" href="{{ $path }}">
            {{ __('common.read_more') }} ⇢
        </a>
    </p>
@endif
