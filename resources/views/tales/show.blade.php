@extends('layouts.app')

@section('title', $tale->title)

@section('main')

    @include('tales.article', ['article' => $tale])
    @include('app.back-to')

@endsection
