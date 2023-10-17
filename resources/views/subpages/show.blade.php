@extends('layouts.app')

@section('title', $subpage->title)

@section('main')

    <article class="subpage">
        @if ($siblings->count() > 1)
        <nav>
            <div class="list-group">
                @foreach ($siblings as $sibling)
                    <a class="list-group-item list-group-item-action{{ $sibling == $subpage ? ' active' : '' }}"
                       href="{{ $sibling->path }}">
                        {{ $sibling->title }}
                    </a>
                @endforeach
            </div>
        </nav>
        @endif

        <section class="body">
            <h1>{{ $subpage->title }}</h1>
            {!! $subpage->body_html !!}
        </section>
    </article>

@endsection
