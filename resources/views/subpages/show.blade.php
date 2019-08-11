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

<div class="container mt-3 mt-sm-4">
    <div class="row">
        <div class="col-sm-5 col-md-4 col-lg-3 mb-3">
            <div class="list-group">
                @foreach ($subpage->siblings()->get() as $sibling)
                    <a class="list-group-item list-group-item-action{{ $sibling == $subpage ? ' active' : '' }}"
                       href="{{ $sibling->path }}">
                        {{ $sibling->title }}
                    </a>
                @endforeach
            </div>
        </div>
        <div class="col-sm-7 col-md-8 col-lg-9">
            <article class="subpage">
                <h1 class="d-none d-sm-block">{{ $subpage->title }}</h1>
                {!! $subpage->body_html !!}
            </article>
        </div>
    </div>
</div>

@endsection
