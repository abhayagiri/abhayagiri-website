@extends('layouts.app')

@section('title', $book->title)

@section('main')

    @include('books.article')

    @include('app.back-to')

@endsection
