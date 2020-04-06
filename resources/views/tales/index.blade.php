@extends('layouts.app')

@include('app.index-breadcrumb-title', ['items' => $tales])

@section('main')

    @foreach ($tales as $i => $article)
        @include('tales.article', [
            'abridge' => ($tales->currentPage() > 1 || $i > 0),
        ])
    @endforeach

    @include('app.pagination', ['items' => $tales])

@endsection
