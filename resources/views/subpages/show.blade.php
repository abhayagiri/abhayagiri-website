@extends('layouts.app')

@section('title', $subpage->title)

@breadcrumb($subpage->title)

@section('main')

    <article class="subpage">
        <nav>
            <div class="list-group">
                @foreach ($subpage->siblings()->get() as $sibling)
                    <a class="list-group-item list-group-item-action{{ $sibling == $subpage ? ' active' : '' }}"
                       href="{{ $sibling->path }}">
                        {{ $sibling->title }}
                    </a>
                @endforeach
            </div>
        </nav>
        <section class="body">
            <h1>{{ $subpage->title }}</h1>
            {!! $subpage->body_html !!}
        </section>
    </article>

@endsection
