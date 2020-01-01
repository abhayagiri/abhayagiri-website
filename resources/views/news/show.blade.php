@extends('layouts.app')

@section('title', $news->title)

@breadcrumb($news->title)

@section('main')

    @include('app.article', ['article' => $news])
    @include('app.back-to')

@endsection
