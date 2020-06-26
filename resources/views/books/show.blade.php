@extends('layouts.app')

@section('title', $book->title)

@section('main')

    @include('books.article')

    @include('app.article-links')

@endsection
