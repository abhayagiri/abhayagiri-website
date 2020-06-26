@extends('layouts.app')

@section('title', $tale->title)

@section('main')

    @include('app.article', ['article' => $tale])

    @include('app.article-links')

@endsection
