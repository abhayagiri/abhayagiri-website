@extends('layouts.app')

@include('app.index-title', ['items' => $reflections])

@section('main')

    @include('app.pagination', ['items' => $reflections, 'top' => true])

    @foreach ($reflections as $i => $article)
        @include('app.article', [
            'abridge' => ($reflections->currentPage() > 1 || $i > 0),
            'showUpdated' => false,
        ])
    @endforeach

    @include('app.pagination', ['items' => $reflections])

@endsection
