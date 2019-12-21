@extends('layouts.app')

@include('app.index-breadcrumb-title', ['items' => $news])

@section('main')

    @foreach ($news as $i => $article)
        @include('app.article', [
            'abridge' => false,
            'showUpdated' => true,
        ])
    @endforeach

    @include('app.pagination', ['items' => $news])

@endsection
