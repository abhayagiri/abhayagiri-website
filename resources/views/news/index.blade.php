@extends('layouts.app')

@include('app.index-title', ['items' => $news])

@section('main')

    @include('app.pagination', ['items' => $news, 'top' => true])

    @foreach ($news as $i => $article)
        @include('news.article', [
            'abridge' => false,
            'showUpdated' => true,
        ])
    @endforeach

    @include('app.pagination', ['items' => $news])

@endsection
