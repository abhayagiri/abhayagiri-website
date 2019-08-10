@extends('layouts.app')

@section('title', $subpage->title)

@push('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ lp($pageMenu['about']->path) }}">
        {{ $pageMenu['about']->title }}
    </a>
</li>
<li class="breadcrumb-item breadcrumb-navpage">
    {{ $subpage->title }}
</li>
@endpush

@section('main')

<article>

{!! $subpage->body_html !!}

</article>

@endsection
