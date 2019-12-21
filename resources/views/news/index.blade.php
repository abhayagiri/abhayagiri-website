@extends('layouts.app')

@include('app.index-breadcrumb-title', ['items' => $news])

@section('main')

    @foreach ($news as $article)
        @include('app.article')
    @endforeach

    @include('app.pagination', ['items' => $news])

@endsection
