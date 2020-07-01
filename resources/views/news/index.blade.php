@extends('layouts.app')

@include('app.index-title', ['items' => $news])

@section('main')

    @include('app.pagination', ['items' => $news, 'top' => true])

    @foreach ($news as $i => $article)
        @include('app.article', [
            'abridge' => ($news->currentPage() > 1 || $i >= setting('home.news_count')->value),
            'showUpdated' => true,
        ])
    @endforeach

    @include('app.pagination', ['items' => $news])

@endsection
