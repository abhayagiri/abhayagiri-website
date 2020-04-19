@extends('layouts.app')

@include('app.index-breadcrumb-title', ['items' => $reflections])

@section('main')

    @foreach ($reflections as $i => $article)
        @include('reflections.article', [
            'abridge' => ($reflections->currentPage() > 1 || $i > 0),
            'showUpdated' => false,
        ])
    @endforeach

    @include('app.pagination', ['items' => $reflections])

@endsection
