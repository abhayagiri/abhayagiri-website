@extends('layouts.app')

@include('app.index-title', ['items' => $tales])

@section('main')

    @include('app.pagination', ['items' => $tales, 'top' => true])

    @foreach ($tales as $i => $article)
        @include('app.article', [
            'abridge' => ($tales->currentPage() > 1 || $i > 0),
        ])
    @endforeach

    @include('app.pagination', ['items' => $tales])

@endsection
