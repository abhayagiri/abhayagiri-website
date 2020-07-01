@extends('layouts.app')

@section('title', $reflection->title)

@section('main')

    @include('app.article', ['article' => $reflection])

    @include('app.article-links')

@endsection
