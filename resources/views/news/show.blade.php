@extends('layouts.app')

@section('title', $news->title)

@section('main')

    @include('app.article', ['article' => $news])

    @include('app.article-links');

@endsection
