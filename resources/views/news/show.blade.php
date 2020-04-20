@extends('layouts.app')

@section('title', $news->title)

@section('main')

    @include('news.article', ['article' => $news])
    @include('app.back-to')

@endsection
